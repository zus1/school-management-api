<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $created_at
 * @property int $product_id
 * @property string $checkout_id
 * @property string $payment_id
 * @property float $sub_total
 * @property float $total
 * @property float $tax
 * @property float $discount
 * @property string $currency
 * @property string $payment_status
 * @property string $flow_status
 * @property string $checkout_url
 * @property int $user_id
 */
#[Attributes([
    ['id', 'payment:success', 'payment:cancel', 'payment:collection', 'payment:retrieve'],
    ['product_id', 'payment:success', 'payment:cancel', 'payment:retrieve'],
    ['checkout_id', 'payment:success', 'payment:cancel', 'payment:retrieve'],
    ['payment_id', 'payment:success', 'payment:cancel', 'payment:retrieve'],
    ['sub_total', 'payment:success', 'payment:cancel', 'payment:retrieve'],
    ['total', 'payment:success', 'payment:cancel', 'payment:collection', 'payment:retrieve'],
    ['tax', 'payment:success', 'payment:cancel', 'payment:retrieve'],
    ['discount', 'payment:success', 'payment:cancel', 'payment:retrieve'],
    ['currency', 'payment:success', 'payment:cancel', 'payment:collection', 'payment:retrieve'],
    ['payment_status', 'payment:success', 'payment:cancel', 'payment:collection', 'payment:retrieve'],
    ['flow_status', 'payment:success', 'payment:cancel', 'payment:collection', 'payment:retrieve'],
    ['checkout_url', 'payment:cancel', 'payment:retrieve'],
    ['invoice', 'payment:success', 'payment:retrieve'],
    ['user', 'payment:collection', 'payment:retrieve'],
    ['product', 'payment:collection', 'payment:retrieve']
])]
class Payment extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'payment_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
