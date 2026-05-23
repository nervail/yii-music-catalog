<?php

namespace backend\modules\api\exceptions;

use Throwable;
use yii\web\UnprocessableEntityHttpException;

class RetryLaterException extends UnprocessableEntityHttpException
{
    public const CODE_DEFAULT = 1000;
    public const CODE_MAIL_FAILED = 1001;
    public const CODE_REG_UNAVAILABLE = 1002;
    public const CODE_SYSTEM_ERROR = 1003;

    public function __construct(int $innerCode = self::CODE_DEFAULT, ?Throwable $previous = null)
    {
        parent::__construct('Please retry Later', $innerCode, $previous);
    }
}