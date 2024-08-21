<?php

namespace App\Http\Controllers\Student;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use App\Repository\StudentRepository;
use Illuminate\Http\JsonResponse;
use Zus1\LaravelAuth\Repository\TokenRepository;
use Zus1\Serializer\Facade\Serializer;

class Onboard
{
    public function __construct(
        private StudentRepository $repository,
        private TokenRepository $tokenRepository,
    ){
    }

    public function __invoke(StudentRequest $request): JsonResponse
    {
        $token = $this->tokenRepository->retrieve($request->query('token'));

        /** @var Student $student */
        $student = $this->repository->findChildByToken($token);
        $student = $this->repository->update($request->input(), $student);

        $this->tokenRepository->deactivate($token);

        return new JsonResponse(Serializer::normalize($student, 'student:onboard'));
    }
}
