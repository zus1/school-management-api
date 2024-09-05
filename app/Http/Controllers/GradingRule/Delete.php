<?php

namespace App\Http\Controllers\GradingRule;

use App\Models\GradingRule;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(GradingRule $gradingRule): JsonResponse
    {
        $gradingRule->delete();

        return new  JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
