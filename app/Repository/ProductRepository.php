<?php

namespace App\Repository;

use App\Models\Product;
use Carbon\Carbon;
use Stripe\Price;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class ProductRepository extends LaravelBaseRepository
{
    protected const MODEL = Product::class;

    public function create(\Stripe\Product $stripeProduct): Product
    {
        $product = new Product();
        $product->created_at = Carbon::now()->setTimestamp($stripeProduct->created)->format('Y-m-d H:i:s');

        $this->modifySharedData($product, $stripeProduct);

        $product->save();

        return $product;
    }

    public function update(\Stripe\Product $stripeProduct, Product $product): Product
    {
        $product->updated_at = Carbon::now()->setTimestamp($stripeProduct->updated)->format('Y-m-d H:i:s');

        $this->modifySharedData($product, $stripeProduct);

        $product->save();

        return $product;
    }

    public function findByProductIdOr404(?string $productId): Product
    {
        if($productId === null) {
            throw new HttpException(400, 'Product id not supplied');
        }

        /** @var Product $product */
        $product = $this->findOneByOr404(['product_id' => $productId]);

        return $product;
    }

    public function addPrice(Price $price, Product $product): Product
    {
        $product->price = $price->unit_amount/100;
        $product->price_id = $price->id;

        $product->save();

        return $product;
    }

    public function updatePrice(Price $price, Product $product): Product
    {
        $product->price = $price->unit_amount/100;

        $product->save();

        return $product;
    }

    private function modifySharedData(Product $product, \Stripe\Product $stripeProduct): void
    {
        $product->name = $stripeProduct->name;
        $product->description = $stripeProduct->description;
        $product->product_id = $stripeProduct->id;
        $product->image = $stripeProduct->images[0] ?? null;
        $product->active = $stripeProduct->active;
    }
}
