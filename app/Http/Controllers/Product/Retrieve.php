<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Product $product): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($product, 'product:retrieve'));
    }
}
