<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TaxNumberConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!$constraint instanceof TaxNumberConstraint) {
            throw new UnexpectedTypeException($constraint, TaxNumberConstraint::class);
        }

        // проверка регулярным выражением на соответствие шаблонам указанным в задаче:
        if (!preg_match('/^((DE{1}[0-9]{9})|(IT{1}[0-9]{11})|(GR{1}[0-9]{9})|(FR{1}[A-Z]{2}[0-9]{9}))?$/', $value)) {
            $this->context
                ->buildViolation($constraint->errorMessage)
                ->addViolation();
        }
    }
}
