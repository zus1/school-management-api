<?php

namespace App\Http\Controllers\SchoolClass;

use App\Http\Requests\SchoolClassRequest;
use App\Models\SchoolClass;
use App\Repository\SchoolClassRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private SchoolClassRepository $repository,
    ){
    }

    public function __invoke(SchoolClassRequest $request, SchoolClass $schoolClass): JsonResponse
    {
        $schoolClass = $this->repository->update($request->all(), $schoolClass);

        return new JsonResponse(Serializer::normalize($schoolClass,
            ['schoolClass:update', 'schoolYear:nestedSchoolClassUpdate', 'teacher:nestedSchoolClassUpdate']));
    }
}
