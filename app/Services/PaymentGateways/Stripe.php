<?php

namespace App\Services\PaymentGateways;
use App\Contracts\PaymentGatewayInterface;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Http;

class Stripe implements PaymentGatewayInterface
{
    protected $initiator_id;
    protected $initiator_type;
    protected $owner_id;
    protected $owner_type;

	/**
	 *
	 * @param Model  $intiator
	 *
	 * @return mixed
	 */
	public function setTransactionInitiator(Model $intiator)
    {
        $this->initiator_id = $intiator->getKey();
        $this->initiator_type = get_class($intiator);

        return $this;
	}

	/**
	 *
	 * @param Model  $owner
	 *
	 * @return mixed
	 */
	public function setTransactionOwner(Model $owner)
    {
        $this->owner_id = $owner->getKey();
        $this->owner_type = get_class($owner);

        return $this;
	}

	/**
	 *
	 * @param string $email
	 * @param int $amount
	 * @param string $currency
	 * @param string $callback
     * @param array $metadata
	 *
	 * @return mixed
	 */
	public function initializeTransaction(string $email, int $amount, string $currency = "usd", string $callback = null, array $metadata = [])
    {
        // if(!$this->owner_id || !$this->owner_type)
        // {
        //     throw new CustomException("Owner is not set");
        // }

        // if(!$this->initiator_id || !$this->initiator_type)
        // {
        //     throw new CustomException("Initiator is not set");
        // }
        // get the payload for stripe
        $payload = [
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
            'line_items' => [
              [
                'price' => 'price_1LhdUGJiWGAt0GoTk4Wp4zpY',
                'quantity' => 1,
              ],
            ],
            'mode' => 'payment',
        ];

        // hit the stripe endpoint
        return Http::stripe()->post('checkout/sessions', $payload);

        // check the response from stripe

        //if the status from the stripe response is not sucessful, throw a custom exception

        //other wise create a pending transaction log
	}
}
