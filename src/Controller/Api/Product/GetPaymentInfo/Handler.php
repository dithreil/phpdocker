<?php

namespace App\Controller\Api\Product\GetPaymentInfo;

use App\Entity\Product;
use App\Model\Payment\Factory\PaymentInfoModelFactory;
use App\Model\Payment\PaymentInfoModel;
use App\Service\Payment\PaymentProviderService;

class Handler
{
    public function __construct(
        private PaymentInfoModelFactory $paymentInfoModelFactory,
        private PaymentProviderService  $paymentProviderService,
    ) {
    }

    public function handle(Product $product, ?string $taxNumber, ?string $couponCode): PaymentInfoModel
    {
        $price = $this->paymentProviderService->calculatePrice($product->getPrice(), $taxNumber, $couponCode);

        return $this->paymentInfoModelFactory->fromParameters($product->getTitle(), $price);
    }
}
