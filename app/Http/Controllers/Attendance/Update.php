<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Repository\AttendanceRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private AttendanceRepository $repository,
    ){
    }

    public  function __invoke(AttendanceRequest $request, Attendance $attendance): JsonResponse
    {
        $attendance = $this->repository->update($request->input(), $attendance);

        return new JsonResponse(Serializer::normalize($attendance, 'attendance:update'));
    }
}
