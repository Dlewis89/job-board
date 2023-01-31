<?php

namespace App\Contracts;

Interface PaymentInterface
{
    public function handle($data);
}
