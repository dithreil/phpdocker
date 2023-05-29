<?php

namespace App\Controller\Api\Product\Purchase;

use App\Controller\Api\Product\Purchase\DTO\PurchaseProductDto;
use App\Model\Payment\Factory\PaymentInfoModelFactory;
use App\Model\Payment\PaymentInfoModel;
use App\Service\Payment\PaymentProviderService;

class Handler
{
    public function __construct(
        private PaymentInfoModelFactory $paymentInfoModelFactory,
        private PaymentProviderService $paymentProviderService,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function handle(PurchaseProductDto $dto): PaymentInfoModel
    {
        $price = $this->paymentProviderService->providePayment(
            $dto->getProduct(),
            $dto->getPaymentProcessor(),
            $dto->getTaxNumber(),
            $dto->getCouponCode(),
        );
        $product = $dto->getProduct();

        return $this->paymentInfoModelFactory->fromParameters($product->getTitle(), $price);
    }
}
