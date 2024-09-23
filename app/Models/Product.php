<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property string $product_id
 * @property string $price_id
 * @property float $price
 * @property string $created_at
 * @property string $updated_at
 * @property bool $active
 */
#[Attributes([
    ['id', 'product:collection', 'product:retrieve', 'product:nestedPaymentRetrieve'],
    ['name', 'product:collection', 'product:retrieve', 'product:nestedPaymentCollection', 'product:nestedPaymentRetrieve'],
    ['description', 'product:collection', 'product:retrieve'],
    ['product_id', 'product:collection', 'product:retrieve'],
    ['created_at', 'product:collection', 'product:retrieve'],
    ['price', 'product:collection', 'product:retrieve'],
    ['active', 'product:collection', 'product:retrieve'],
])]
class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'product_id', 'id');
    }
}
