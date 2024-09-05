<?php

namespace App\Http\Controllers\GradingRule;

use App\Models\GradingRule;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(GradingRule $gradingRule): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($gradingRule,
            ['gradingRule:retrieve', 'gradeRange:nestedGradingRuleRetrieve']));
    }
}
