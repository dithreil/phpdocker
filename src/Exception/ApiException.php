<?php

namespace App\Exception;

abstract class ApiException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public $message = 'Api exception';

    abstract public function toArray(): array;
}
