<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class TaxNumberConstraint extends Constraint
{
    public string $errorMessage = 'Wrong tax number';
}
