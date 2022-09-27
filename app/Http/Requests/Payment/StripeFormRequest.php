<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use App\Exceptions\CustomException;

class StripeFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->hasHeader('Stripe-Signature')) {
            $sig = $this->header('Stripe-Signature');
            info($sig);
            $data = explode(",", $sig]);
            $time_split = explode("=", $data[0]);
            $v1_split = explode("=", $data[1]);
            $v0_split = ($data[2] ?? false) ? explode("=", $data[2]) : null;
            $timestamp = ($time_split[1]);
            $signed_payload = $timestamp. '.' . json_encode($this->all());
            $expected_sig = hash_hmac('sha256', config('app.stripe_secret_key'), $signed_payload);
            if(Carbon::createFromTimestamp(time())->diffInDays(now()) >= config('app.stripe_tolerance')){
                throw new CustomException("Webhook tolerance exceeded", 400);
            };
            return ($expected_sig === $v1_split[1] || $expected_sig === $v0_split ? $v0_split[1] : false);
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
