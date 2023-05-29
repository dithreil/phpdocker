<?php

namespace Unit\Service;

use App\Service\Payment\PaymentProviderService;
use App\Service\Payment\PaypalPaymentProcessor;
use App\Service\Payment\StripePaymentProcessor;
use PHPUnit\Framework\TestCase;

class PaymentProviderServiceTest extends TestCase
{
    private PaymentProviderService $paymentProviderService;

    protected function setUp(): void
    {
        parent::setUp();
        $stripePaymentProcessor = $this->getMockBuilder(StripePaymentProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();
        $paypalPaymentProcessor = $this->getMockBuilder(PaypalPaymentProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentProviderService = new PaymentProviderService(
            $paypalPaymentProcessor,
            $stripePaymentProcessor
        );
    }

    /**
     * @dataProvider priceDataProvider
     */
    public function testCalculatePriceSuccessfully(
        int $basePrice,
        ?string $taxNumber,
        ?string $couponCode,
        float $expectedPrice
    ): void {
        $price = $this->paymentProviderService->calculatePrice($basePrice, $taxNumber, $couponCode);
        self::assertEquals($expectedPrice, $price);
    }

    public function priceDataProvider(): array
    {
        return [
            [
                10000,
                'DE123456789',
                'D10',
                107.1,
            ],
            [
                10000,
                'IT12345678910',
                'D100',
                0.01,
            ],
            [
                10000,
                'GR123456789',
                'P50',
                62.,
            ],
            [
                10000,
                'FRZZ123456789',
                null,
                100.,
            ],
        ];
    }
}
