<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class CouponCodeConstraint extends Constraint
{
    public string $errorMessage = 'Wrong coupon code';
}
