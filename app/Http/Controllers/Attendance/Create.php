<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Requests\AttendanceRequest;
use App\Repository\AttendanceRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private AttendanceRepository $repository,
    ){
    }

    public function __invoke(AttendanceRequest $request): JsonResponse
    {
        $attendance = $this->repository->create($request->input());

        return new JsonResponse(Serializer::normalize($attendance, [
            'attendance:create',
            'teacher:nestedAttendanceCreate',
            'student:nestedAttendanceCreate',
            'schoolClass:nestedAttendanceCreate',
            'subject:nestedAttendanceCreate'
        ]));
    }
}
