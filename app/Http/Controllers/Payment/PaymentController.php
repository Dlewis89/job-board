<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\CustomException;
use App\Models\TransactionLog;
use App\Models\Job;
use App\Http\Requests\Payment\StripeFormRequest;

class PaymentController extends Controller
{
    public function webhook(StripeFormRequest $request)
    {
        try {
            $type = $request['type'];
            $data = $request['data']['object'];
            switch($type) {
                case 'payment_intent.succeeded':
                    return $this->handlePaymentSuccessful($data);
                case 'payment_intent.failed':
                    return $this->handlePaymentFailed($data);
                default:
                    throw new CustomException('Something went wrong');
            }
        } catch(CustomException $e) {
            return response()->errorResponse($e->getMessage(), [], $e->getCode());
        } catch(\Exception $e) {
            return response()->errorResponse('Something went wrong!');
        }
    }

    public function handlePaymentSuccessful($data)
    {
        $transaction =  TransactionLog::where('reference', $data['metadata']['reference'])->firstOrFail();
        if($transaction->status === 'success'){
            throw new CustomException('Duplicate webhook request', 400);
        }
        $transaction->update(['status' => 'success']);
        Job::find($data['metadata']['job_id'])->update(['is_featured' => true]);
        return response()->success('Success');
    }

    public function handlePaymentFailed($data)
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
