<?php

namespace App\Http\Controllers\Grade;

use App\Http\Controllers\BaseSchoolDirectoryCollectionController;
use App\Repository\GradeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollection extends BaseSchoolDirectoryCollectionController
{
    public function __construct(GradeRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->setCollectionRelations();

        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection, [
            'grade:collection',
            'teacher:nestedGradeCollection',
            'student:nestedGradeCollection',
            'schoolClass:nestedGradeCollection',
            'subject:nestedGradeCollection'
        ]));
    }
}
