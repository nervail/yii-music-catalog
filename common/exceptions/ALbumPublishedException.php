<?php

namespace common\exceptions;

use Throwable;
use yii\web\UnprocessableEntityHttpException;

class ALbumPublishedException extends UnprocessableEntityHttpException
{
    public function __construct(string $message = 'Album already published', int $code = 422, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}