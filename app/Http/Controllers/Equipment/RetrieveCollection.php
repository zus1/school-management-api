<?php

namespace App\Http\Controllers\Equipment;

use App\Repository\EquipmentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollection extends BaseCollectionController
{
    public function __construct(EquipmentRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(Request $request)
    {
        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection, 'equipment:collection'));
    }
}
