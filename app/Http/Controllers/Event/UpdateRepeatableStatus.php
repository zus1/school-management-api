<?php

namespace App\Http\Controllers\Event;

use App\Models\Event;
use App\Repository\EventRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class UpdateRepeatableStatus
{
    public function __construct(
        private EventRepository $repository,
    ){
    }

    public function __invoke(Request $request, Event $event): JsonResponse
    {
        $event = $this->repository->updateRepeatableStatus($event, $request->query('repeatable_status'));

        return new JsonResponse(Serializer::normalize($event, 'event:updateRepeatableStatus'));
    }
}
