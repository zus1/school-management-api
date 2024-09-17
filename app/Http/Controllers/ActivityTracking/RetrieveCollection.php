<?php

namespace App\Http\Controllers\ActivityTracking;

use App\Http\Controllers\CustomBaseCollectionController;
use App\Repository\ActivityTrackingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollection extends CustomBaseCollectionController
{
    public function __construct(ActivityTrackingRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->authRelationFilters->setForActivityTrackingCollectionController($this);

        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection,
            ['activityTracking:collection', 'student:nestedActivityTrackingCollection']));
    }
}
