<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\CustomBaseCollectionController;
use App\Http\Requests\AttendanceRequest;
use App\Repository\AttendanceRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Aggregate extends CustomBaseCollectionController
{
    public function __construct(
        private AttendanceRepository $repository,
    ){
        parent::__construct($this->repository);
    }

    public function __invoke(AttendanceRequest $request): JsonResponse
    {
        $this->authRelationFilters->setForController($this);

        $aggregated = $this->repository->aggregate(
            $request->input(),
            $this->collectionRelations
        );

        return new JsonResponse(Serializer::normalize($aggregated, [
            'attendance:aggregate',
            'teacher:nestedAttendanceAggregate',
            'student:nestedAttendanceAggregate',
            'schoolClass:nestedAttendanceAggregate',
            'subject:nestedAttendanceAggregate'
        ]));
    }
}
