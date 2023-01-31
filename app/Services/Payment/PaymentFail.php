<?php

namespace App\Services\Payment;

use App\Contracts\PaymentInterface;
use App\Exceptions\CustomException;
use App\Models\TransactionLog;

class PaymentFail implements PaymentInterface
{
    public function handle($data)
    {
        $transaction =  TransactionLog::where('reference', $data['metadata']['reference'])->firstOrFail();
        if($transaction->status === 'success'){
            throw new CustomException('Status already updated.', 400);
        }
        if($transaction->status === 'failed'){
            throw new CustomException('Duplicate webhook request', 400);
        }
        $transaction->update(['status' => 'failed']);

        return response()->success('Success');
    }
}
