<?php

namespace App\Services\Stripe;

use App\Interface\WebhookProcessorInterface;
use App\Repository\ProductRepository;
use Illuminate\Support\Facades\Log;
use Stripe\Price;
use Stripe\Product;
use Stripe\Webhook;

class ProductEventProcessor implements WebhookProcessorInterface
{
    private const CREATED = 'product.created';
    private const UPDATED = 'product.updated';
    private const DELETED = 'product.deleted';
    private const PRICE_CREATED = 'price.created';
    private const PRICE_UPDATED = 'price.updated';

    public function __construct(
        private ProductRepository $repository,
    ){
    }

    public function process(string $payload, string $signature): void
    {
        $event = Webhook::constructEvent($payload, $signature, config('stripe.product_webhook_secret'));
        $object = $event->data->object;

        try {
            if($event->type === self::CREATED && $object instanceof Product) {
                $this->repository->create($object);
            }
            if($event->type === self::UPDATED && $object instanceof Product) {
                $this->processUpdate($object);
            }
            if($event->type === self::DELETED && $object instanceof Product) {
                $this->processDelete($object);
            }
            if($event->type === self::PRICE_CREATED && $object instanceof Price) {
                Log::info('Price create triggered '.$event->type, $object->toArray());
                $this->processPriceCreate($object);
            }
            if($event->type === self::PRICE_UPDATED && $object instanceof Price) {
                $this->processPriceUpdate($object);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $object->toArray());
        }
    }

    private function processUpdate(Product $object): void
    {
        $dbProduct = $this->repository->findByProductIdOr404($object->id);

        $this->repository->update($object, $dbProduct);
    }

    private function processDelete(Product $object): void
    {
        $dbProduct = $this->repository->findByProductIdOr404($object->id);

        $dbProduct->delete();
    }

    private function processPriceCreate(Price $object): void
    {
        $dbProduct = $this->repository->findByProductIdOr404($object->product);

        $this->repository->addPrice($object, $dbProduct);
    }

    private function processPriceUpdate(Price $object): void
    {
        $dbProduct = $this->repository->findByProductIdOr404($object->product);

        $this->repository->updatePrice($object, $dbProduct);
    }
}
