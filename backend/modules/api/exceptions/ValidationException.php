<?php

namespace backend\modules\api\exceptions;

use yii\web\HttpException;

class ValidationException extends HttpException
{
    public $errors = [];

    public function __construct(array $errors, $message = 'Validation failed', $code = 422)
    {
        $this->errors = $errors;
        parent::__construct($code, $message);
    }
}