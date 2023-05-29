<?php

namespace App\Validator;

use App\Service\Payment\PaymentProviderService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CouponCodeConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!$constraint instanceof CouponCodeConstraint) {
            throw new UnexpectedTypeException($constraint, CouponCodeConstraint::class);
        }

        $valueLength = strlen($value);
        if ($valueLength < 2) {
            $this->context
                ->buildViolation($constraint->errorMessage)
                ->addViolation();

            return;
        }

        $prefix = strtoupper(substr($value, 0 , 1));
        if (PaymentProviderService::COUPON_PREFIX_DIGIT !== $prefix
            && PaymentProviderService::COUPON_PREFIX_PERCENT !== $prefix) {
            $this->context
                ->buildViolation($constraint->errorMessage)
                ->addViolation();

            return;
        }

        $amount = intval(substr($value, 1));
        if (0 >= $amount) {
            $this->context
                ->buildViolation($constraint->errorMessage)
                ->addViolation();
        }

        if (PaymentProviderService::COUPON_PREFIX_PERCENT === $prefix && $amount > 99) {
            $this->context
                ->buildViolation($constraint->errorMessage)
                ->addViolation();
        }
    }
}
