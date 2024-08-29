<?php

namespace App\Http\Controllers\Subject;

use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use App\Repository\SubjectRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private SubjectRepository $repository,
    ){
    }

    public function __invoke(SubjectRequest $request, Subject $subject): JsonResponse
    {
        $subject = $this->repository->update($request->input(), $subject);

        return new JsonResponse(Serializer::normalize($subject, ['subject:update', 'schoolYear:nestedSubjectUpdate']));
    }
}
