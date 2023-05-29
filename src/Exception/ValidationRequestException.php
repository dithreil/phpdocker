<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class ValidationRequestException extends ValidatorException
{
    public function __construct(private ConstraintViolationListInterface $constraintViolationList)
    {
        parent::__construct('Error validation request', 400);
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}
