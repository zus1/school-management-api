<?php

namespace App\Http\Controllers\SchoolClass;

use App\Http\Requests\SchoolClassRequest;
use App\Repository\SchoolClassRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private SchoolClassRepository $repository,
    ){
    }

    public function __invoke(SchoolClassRequest $request): JsonResponse
    {
        $schoolClass = $this->repository->create($request->input());

        return new JsonResponse(Serializer::normalize($schoolClass,
            ['schoolClass:create', 'schoolYear:nestedSchoolClassCreate', 'teacher:nestedSchoolClassCreate']));
    }
}
