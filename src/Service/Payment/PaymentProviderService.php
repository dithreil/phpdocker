<?php

namespace App\Service\Payment;

use App\Entity\Product;

class PaymentProviderService
{
    public const COUPON_PREFIX_DIGIT = 'D';
    public const COUPON_PREFIX_PERCENT = 'P';
    public const PAYPAL_PAYMENT_PROCESSOR = 'paypal';
    public const STRIPE_PAYMENT_PROCESSOR = 'stripe';
    public const TAX_PREFIX_DE = 'DE';
    public const TAX_PREFIX_IT = 'IT';
    public const TAX_PREFIX_GR = 'GR';
    public const TAX_PREFIX_FR = 'FR';
    private const TAX_RATE_MAP = [
        self::TAX_PREFIX_DE => 19,
        self::TAX_PREFIX_IT => 22,
        self::TAX_PREFIX_GR => 24,
        self::TAX_PREFIX_FR => 0,
    ];

    public function __construct(
        private PaypalPaymentProcessor $paypalPaymentProcessor,
        private StripePaymentProcessor $stripePaymentProcessor,
    ) {
    }

    public function calculatePrice(int $basePrice, ?string $taxNumber, ?string $couponCode): float
    {
        $couponDiscount = 0;
        if (null !== $couponCode) {
            $couponDiscountType = substr($couponCode, 0, 1);
            switch ($couponDiscountType) {
                case self::COUPON_PREFIX_DIGIT:
                    $couponDiscount = intval(substr($couponCode, 1)) * 100;
                    if ($couponDiscount >= $basePrice) {
                        return 0.01;
                    }
                    break;
                case self::COUPON_PREFIX_PERCENT:
                    $percentDiscount = intval(substr($couponCode, 1));
                    $couponDiscount = $basePrice / 100 * $percentDiscount;
                    break;
            }
        }

        $taxPercent = 0;
        if (null !== $taxNumber) {
            $taxNumberPrefix = substr($taxNumber, 0, 2);
            $taxPercent = self::TAX_RATE_MAP[$taxNumberPrefix];
        }

        $priceWithDiscount = $basePrice - $couponDiscount;

        if ($taxPercent > 0) {
            $fullPrice = round(($priceWithDiscount + ($priceWithDiscount / 100 * $taxPercent)) / 100, 2);
        } else {
            $fullPrice = $priceWithDiscount / 100;
        }

        return $fullPrice;
    }

    /**
     * @throws \Exception
     */
    public function providePayment(
        Product $product,
        string $paymentProcessor,
        ?string $taxNumber,
        ?string $couponCode,
    ): float {
        $price = $this->calculatePrice($product->getPrice(), $taxNumber, $couponCode);
        switch ($paymentProcessor) {
            case self::PAYPAL_PAYMENT_PROCESSOR:
                $this->paypalPaymentProcessor->processPayment($price);
                break;
            case self::STRIPE_PAYMENT_PROCESSOR:
                $this->stripePaymentProcessor->processPayment($price);
                break;
        }

        return $price;
    }
}
