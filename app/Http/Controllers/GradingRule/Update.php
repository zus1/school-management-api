<?php

namespace App\Http\Controllers\GradingRule;

use App\Http\Requests\GradingRuleRequest;
use App\Models\GradingRule;
use App\Repository\GradingRuleRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private GradingRuleRepository $repository,
    ){
    }

    public function __invoke(GradingRuleRequest $request,  GradingRule $gradingRule): JsonResponse
    {
        $gradingRule = $this->repository->update($request->input(), $gradingRule);

        return new JsonResponse(Serializer::normalize($gradingRule, 'gradingRule:update'));
    }
}
