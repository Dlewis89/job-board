<?php

namespace App\Http\Controllers\Payment;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StripeFormRequest;
use App\Models\Job;
use App\Services\Webhook\StripeWebhookService;
use Exception;


class PaymentController extends Controller
{
    public function __construct(private StripeWebhookService $stripeWebhookService)
    {
    }
    public function webhook(StripeFormRequest $request)
    {
        try {
            $this->stripeWebhookService->handle($request);
        } catch(CustomException $e) {
            return response()->errorResponse($e->getMessage(), [], $e->getCode());
        } catch(Exception $e) {
            return response()->errorResponse('Something went wrong!');
        }
    }
}
