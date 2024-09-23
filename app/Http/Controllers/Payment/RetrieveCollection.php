<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\CustomBaseCollectionController;
use App\Repository\PaymentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollection extends CustomBaseCollectionController
{
    public function __construct(PaymentRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->authRelationFilters->setForRetrievePaymentsCollectionController($this);

        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection,
            ['payment:collection', 'product:nestedPaymentCollection', 'user:nestedPaymentCollection']));
    }
}
