<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PaymentGatewayInterface
{
    public function setTransactionInitiator(Model $intiator);

    public function setTransactionOwner(Model $owner);

    public function initializeTransaction(string $email, int $amount, string $currency = "usd", string $callback = null, array $metadata = []);
}
