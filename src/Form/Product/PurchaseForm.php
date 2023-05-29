<?php

namespace App\Form\Product;

use App\Entity\Product;
use App\Form\ApiForm;
use App\Service\Payment\PaymentProviderService;
use App\Validator\CouponCodeConstraint;
use App\Validator\TaxNumberConstraint;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class PurchaseForm extends ApiForm
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('taxNumber', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new TaxNumberConstraint(),
                ],
            ])
            ->add('paymentProcessor', ChoiceType::class, [
                'choices' => [
                    PaymentProviderService::PAYPAL_PAYMENT_PROCESSOR,
                    PaymentProviderService::STRIPE_PAYMENT_PROCESSOR,
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('couponCode', TextType::class, [
                'constraints' => [
                    new CouponCodeConstraint(),
                ],
            ]);
    }
}
