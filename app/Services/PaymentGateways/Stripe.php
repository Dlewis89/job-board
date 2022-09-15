<?php

namespace App\Services\PaymentGateways;
use App\Contracts\PaymentGatewayInterface;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Http;
use App\Models\TransactionLog;

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
	public function initializeTransaction(string $email, int $amount = 0, string $priceId = null, string $currency = "usd", string $callback = null, array $metadata = [])
    {
        if (!$this->owner_id || !$this->owner_type) {
            throw new CustomException("Owner is not set", 400);
        }

        if (!$this->initiator_id || !$this->initiator_type) {
            throw new CustomException("Initiator is not set", 400);
        }

        if (!$priceId && $amount <= 0) {
            throw new CustomException("Invalid amount", 400);
        }

        if ($amount > 0) {
            // create product
            $product = Http::stripe()->post('products', [
                'name' => 'Job board payment'
            ]);
            // use product id to create price
            $price = Http::stripe()->post('prices', [
                'product' => $product['id'],
                'unit_amount' => $amount * 100,
                'currency' => $currency
            ]);
            // set price id
            $priceId = $price['id'];
        } else {
            $getPrice = Http::stripe()->get('prices/' . $priceId);
            // handle exception
            $amount = $getPrice['unit_amount'] / 100;
        }
        // get the payload for stripe
        $payload = [
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
            'line_items' => [
              [
                'price' => $priceId,
                'quantity' => 1,
              ],
            ],
            'mode' => 'payment',
        ];

        // hit the stripe endpoint
        $data = Http::stripe()->post('checkout/sessions', $payload)->json();

        // check the response from stripe

        //if the status from the stripe response is not sucessful, throw a custom exception
        if (!array_key_exists('url', $data)){
            throw new CustomException('invalid response', 400);
        }
        //other wise create a pending transaction log
        TransactionLog::create([
            'provider' => class_basename($this),
            'reference' => PaymentService::generateRef(),
            'amount' => $amount,
            'initiator_id' => $this->initiator_id,
            'initiator_type' => $this->initiator_type,
            'owner_id' => $this->owner_id,
            'owner_type' => $this->owner_type,
            'metadata' => json_encode($metadata),
            'created_at' => now()
        ]);

        return $data;
	}
}
