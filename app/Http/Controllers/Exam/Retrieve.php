<?php

namespace App\Http\Controllers\Exam;

use App\Models\Exam;
use Symfony\Component\HttpFoundation\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Exam $exam): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($exam,
            ['exam:retrieve', 'subject:nestedExamRetrieve', 'teacher:nestedExamRetrieve', 'gradingRule:nestedExamRetrieve']));
    }
}
