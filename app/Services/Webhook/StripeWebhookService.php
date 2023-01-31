<?php

namespace App\Services\Webhook;

use App\Exceptions\CustomException;
use App\Services\Payment\PaymentSuccess;
use App\Services\Payment\PaymentFail;

class StripeWebhookService
{
    public function __construct(
        private PaymentSuccess $paymentSuccess,
        private PaymentFail $paymentFail
    ){}

    public function handle($request)
    {
        $type = $request['type'];
        $data = $request['data']['object'];
        switch($type) {
            case 'payment_intent.succeeded':
                return $this->paymentSuccess->handle($data);
            case 'payment_intent.failed':
                return $this->paymentFail->handle($data);
            default:
                throw new CustomException('Something went wrong');
        }
    }

}
