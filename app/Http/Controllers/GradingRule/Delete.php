<?php

namespace App\Http\Controllers\GradingRule;

use App\Models\GradingRule;
use App\Repository\GradingRuleRepository;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __construct(
        private GradingRuleRepository $repository,
    ){
    }

    public function __invoke(GradingRule $gradingRule): JsonResponse
    {
        $this->repository->delete($gradingRule);

        return new  JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
