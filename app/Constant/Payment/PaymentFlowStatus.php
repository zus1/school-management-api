<?php

namespace App\Constant\Payment;

use App\Constant\Constant;

class PaymentFlowStatus extends Constant
{
    public final const IN_PROGRESS = 'in_progress';
    public final const FINISHED = 'finished';
    public final const CANCELED = 'canceled';
    public final const EXPIRED = 'expired';
    public final const PENDING_INVOICE = 'pending_invoice';

    public static function ongoing(string $flowStatus): bool
    {
        return in_array($flowStatus, [self::CANCELED, self::IN_PROGRESS]);
    }
}
