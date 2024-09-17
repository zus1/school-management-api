<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\CustomBaseCollectionController;
use App\Repository\AttendanceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollectionCustom extends CustomBaseCollectionController
{
    public function __construct(AttendanceRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->authRelationFilters->setForController($this);

        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection, [
            'attendance:collection',
            'teacher:nestedAttendanceCollection',
            'student:nestedAttendanceCollection',
            'schoolClass:nestedAttendanceCollection',
            'subject:nestedAttendanceCollection'
        ]));
    }
}
