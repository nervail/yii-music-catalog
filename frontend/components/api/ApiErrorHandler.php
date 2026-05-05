<?php

namespace frontend\components\api;

use frontend\exceptions\ValidationException;
use yii\web\ErrorHandler;
use yii\web\Response;
use yii\web\HttpException;

class ApiErrorHandler extends ErrorHandler
{
    protected function renderException($exception)
    {
        $route = \Yii::$app->requestedRoute ?? '';
        $isApi = str_starts_with($route, 'api/');
        
        if (!$isApi) {
            return parent::renderException($exception);
        }

        $response = \Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $code = $exception instanceof HttpException
            ? $exception->statusCode
            : 500;

        $message = YII_DEBUG
            ? $exception->getMessage()
            : ($exception instanceof HttpException ? $exception->getMessage() : 'Internal server error');


        $data = [
            'success' => false,
            'error' => [
                'message' => $message,
                'code' => $code,
            ],
        ];

        if ($exception instanceof ValidationException) {
            $data['error']['errors'] = $exception->errors;
        }

        $response->statusCode = $code;
        $response->data = $data;

        $response->send();
        return;
    }
}