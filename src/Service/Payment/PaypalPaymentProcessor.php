<?php

namespace App\Service\Payment;

use Exception;

class PaypalPaymentProcessor implements InterfacePayment
{
    /**
     * @throws Exception in case of a failed payment
     */
    public function processPayment(int $price): void
    {
        if ($price > 100) {
            throw new Exception('Too high price');
        }
    }
}