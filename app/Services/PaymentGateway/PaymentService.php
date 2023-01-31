<?php

namespace App\Services\PaymentGateway;

use App\Models\TransactionLog;

class PaymentService
{
    public static function generateRef()
    {
        $ref = 'JOB_BOARD_' . mt_rand(100000, 9999999) . uniqId(time());

        $transaction_log = TransactionLog::whereReference($ref)->exists();

        while ($transaction_log) {
            self::generateRef();
        }

        return $ref;
    }
}
