<?php

namespace App\Listeners;

use App\Models\Question;
use Zus1\Serializer\Event\NormalizedDataEvent;
use Zus1\Serializer\Normalizer\Normalizer;

class OrderAnswersByPositionListener
{
    /**
     * Handle the event.
     */
    public function handle(NormalizedDataEvent $event): void
    {
        if($event->getSubjectClass() !== Question::class) {
            return;
        }

        /** @var Normalizer $normalizer */
        $normalizer = $event->getNormalizer();
        $data = $normalizer->getNormalizedData();

        if($data === []) {
            return;
        }

        $results = [];
        if($this->isSubArrays($data)) {
            foreach ($data as $key => $questionArr) {
                $results[$key] = $this->orderAnswers($questionArr);
            }
        } else {
            $results = $this->orderAnswers($data);
        }

        $normalizer->setNormalizedData($results);
    }

    private function orderAnswers(array $questionArr): array
    {
        if(!isset($questionArr['answers'])) {
            return $questionArr;
        }

        usort($questionArr['answers'], function (array $answer1, array $answer2) {
            return (int)$answer1['position'] > (int)$answer2['position'];
        });

        return $questionArr;
    }

    private function isSubArrays(array $payload): bool
    {
        return array_filter($payload, 'is_array') == $payload;
    }
}
