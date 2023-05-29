<?php

namespace App\Service\Payment;

interface InterfacePayment
{
    public function processPayment(int $price): void;
}
