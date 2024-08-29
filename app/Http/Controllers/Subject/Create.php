<?php

namespace App\Http\Controllers\Subject;

use App\Dto\SubjectWithLecturersResponseDto;
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

        return new JsonResponse(SubjectWithLecturersResponseDto::create($subject, 'subject:create'));
    }
}
