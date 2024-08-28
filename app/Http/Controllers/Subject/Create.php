<?php

namespace App\Http\Controllers\Subject;

use App\Http\Requests\SubjectRequest;
use App\Repository\SubjectRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private SubjectRepository $repository,
    ){
    }

    public function __invoke(SubjectRequest $request): JsonResponse
    {
        $subject = $this->repository->create($request->input());

        return new JsonResponse(Serializer::normalize($subject,
            ['subject:create', 'teacher:nestedSubjectCreate', 'schoolYear:nestedSubjectCreate']));
    }
}
