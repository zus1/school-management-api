<?php

namespace App\Http\Controllers\Student;

use App\Constant\CustomTokenType;
use App\Http\Requests\StudentRequest;
use App\Mail\InvitationMail;
use App\Repository\StudentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Zus1\LaravelAuth\Repository\TokenRepository;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private StudentRepository $repository,
        private TokenRepository $tokenRepository,
    ){
    }

    public function __invoke(StudentRequest $request)
    {
        $student = $this->repository->create($request->input());

        $token = $this->tokenRepository->create($student, CustomTokenType::INVITATION);
        Mail::to($student->email)->send(new InvitationMail($token));

        return new JsonResponse(Serializer::normalize($student, 'student:create'));
    }
}
