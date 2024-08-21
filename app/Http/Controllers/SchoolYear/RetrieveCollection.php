<?php

namespace App\Http\Controllers\SchoolYear;

use App\Repository\SchoolYearRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;

class RetrieveCollection extends BaseCollectionController
{
    public function __construct(SchoolYearRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $collection = $this->retrieveCollection($request);

        return new JsonResponse($collection->toArray());
    }
}
