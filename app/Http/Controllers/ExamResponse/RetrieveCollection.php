<?php

namespace App\Http\Controllers\ExamResponse;

use App\Http\Requests\ExamResponseRequest;
use App\Models\ExamSession;
use App\Repository\ExamResponseRepository;
use Illuminate\Http\JsonResponse;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;

class RetrieveCollection extends BaseCollectionController
{
    public function __construct(ExamResponseRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(ExamResponseRequest $request, ExamSession $examSession): JsonResponse
    {
        $filters = $request->query('filters');
        $filters = [
            ...$filters,
            'exam_session_id' => $examSession->id,
        ];
        $request->query->set('filters', $filters);

        $collection = $this->retrieveCollection($request);

        return new JsonResponse($collection, ['examResponse:collection', 'question:nestedExamCollection']);
    }
}
