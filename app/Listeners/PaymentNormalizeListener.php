<?php

namespace App\Listeners;

use App\Constant\RouteName;
use App\Models\Payment;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Zus1\Serializer\Event\NormalizedDataEvent;
use Zus1\Serializer\Normalizer\Normalizer;

class PaymentNormalizeListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private Request $request,
        private UserRepository $repository,
    ){
    }

    /**
     * Handle the event.
     */
    public function handle(NormalizedDataEvent $event): void
    {
        if($this->supported($event) === false) {
            return;
        }

        /** @var Normalizer $normalizer */
        $normalizer = $event->getNormalizer();
        $data = $normalizer->getNormalizedData();

        $userId = $this->getUserId($data);
        $child = $this->getChildUser($userId);

        $data['user']['id'] = $child->id;

        $normalizer->setNormalizedData($data);
    }

    private function supported(NormalizedDataEvent $event): bool
    {
        $class = $event->getSubjectClass();
        $routeName = $this->request->route()->getName();

        if($class !== Payment::class || $routeName !== RouteName::PAYMENT) {
            return false;
        }

        return true;
    }

    private function getUserId(array $data): ?int
    {
        return $data['user']['id'] ?? null;
    }

    private function getChildUser(?int $parentUserId): ?User
    {
        /** @var ?User $parent */
        $parent = $this->repository->findOneBy(['id' => $parentUserId]);
        if($parent === null) {
            return null;
        }

        return $this->repository->findChildByParent($parent);
    }
}
