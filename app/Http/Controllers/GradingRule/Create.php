<?php

namespace App\Http\Controllers\GradingRule;

use App\Http\Requests\GradingRuleRequest;
use App\Repository\GradingRuleRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private GradingRuleRepository $repository,
    ){
    }

    public function __invoke(GradingRuleRequest $request): JsonResponse
    {
        $gradingRule = $this->repository->create($request->input());

        return new JsonResponse(Serializer::normalize($gradingRule, 'gradingRule:create'));
    }
}
