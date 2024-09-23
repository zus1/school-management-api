<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $created_at
 * @property string $invoice_id
 * @property string $url
 * @property string $pdf_url
 * @property int $payment_id
 */
#[Attributes([
    ['id', 'invoice:nestedPaymentSuccess', 'invoice:nestedPaymentRetrieve'],
    ['created_at', 'invoice:nestedPaymentSuccess'],
    ['url', 'invoice:nestedPaymentSuccess'],
    ['pdf_url', 'invoice:nestedPaymentSuccess'],
])]
class Invoice extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
