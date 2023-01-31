<?php

namespace App\Services\Payment;

use App\Contracts\PaymentInterface;
use App\Exceptions\CustomException;
use App\Models\TransactionLog;
use App\Models\Job;

class PaymentSuccess implements PaymentInterface
{
    public function handle($data)
    {
        $transaction =  TransactionLog::where('reference', $data['metadata']['reference'])->firstOrFail();
        if($transaction->status === 'success'){
            throw new CustomException('Duplicate webhook request', 400);
        }
        $transaction->update(['status' => 'success']);
        Job::find($data['metadata']['job_id'])->update(['is_featured' => true]);
        return response()->success('Success');
    }
}
