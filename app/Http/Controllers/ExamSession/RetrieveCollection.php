<?php

namespace App\Http\Controllers\ExamSession;

use App\Http\Requests\ExamSessionRequest;
use App\Models\Exam;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Repository\ExamSessionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollection extends BaseCollectionController
{
    public function __construct(ExamSessionRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(ExamSessionRequest $request, Exam $exam): JsonResponse
    {
        $auth = Auth::user();

        if($auth instanceof Teacher) {
            $this->addCollectionRelation([
                'relation' => 'exam',
                'field' => 'teacher_id',
                'value' => $auth->id,
            ]);
        }
        if($auth instanceof Student) {
            $this->addCollectionRelation([
                'relation' => 'student',
                'field' => 'id',
                'value' => $auth->id,
            ]);
        }
        if($auth instanceof Guardian) {
            $students = $auth->students()->get();

            /** @var Student $student */
            foreach ($students as $student) {
                $this->addCollectionRelation([
                    'relation' => 'student',
                    'field' => 'id',
                    'value' => $student->id
                ]);
            }
        }

        $filters = $request->query('filters');
        $filters = [
            ...$filters,
            'exam_id' => $exam->id,
        ];
        $request->query->set('filters', $filters);

        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection,
            ['examSession:collection', 'student:nestedExamCollection']));
    }
}
