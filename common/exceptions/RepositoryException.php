<?php

namespace common\exceptions;

use Throwable;
use yii\web\UnprocessableEntityHttpException;

class RepositoryException extends UnprocessableEntityHttpException
{
    public function __construct(string $message = 'Error while saving', int $code = 422, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}