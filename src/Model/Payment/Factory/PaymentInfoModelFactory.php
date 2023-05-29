<?php

namespace App\Model\Payment\Factory;

use App\Model\Payment\PaymentInfoModel;

class PaymentInfoModelFactory
{
    public function fromParameters(?string $productTitle, ?float $price): PaymentInfoModel
    {
        $model = new PaymentInfoModel();

        return $model
            ->setProductName($productTitle)
            ->setPrice($price);
    }
}
