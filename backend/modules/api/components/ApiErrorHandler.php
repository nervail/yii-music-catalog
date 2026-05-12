<?php

namespace backend\modules\api\components;

use backend\modules\api\exceptions\ValidationException;
use yii\web\ErrorHandler;
use yii\web\Response;
use yii\web\HttpException;

class ApiErrorHandler extends ErrorHandler
{
    protected function renderException($exception)
    {
        $isApi = (\Yii::$app->controller && \Yii::$app->controller->module->id === 'api')
             || str_starts_with(\Yii::$app->request->pathInfo, 'api/');
        
        if (!$isApi) {
            parent::renderException($exception);
            return;
        }

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $response = \Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $code = $exception instanceof HttpException
            ? $exception->statusCode
            : 500;

        $message = $exception instanceof HttpException
            ? $exception->getMessage()
            : (YII_DEBUG ? $exception->getMessage() : 'Internal server error');


        if ($code >= 500) {
            \Yii::error([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ], 'api-critical');
        }

        $data = [
            'success' => false,
            'error' => [
                'message' => $message,
                'code' => $code,
            ],
        ];

        if ($exception instanceof ValidationException) {
            $data['error']['validation_errors'] = $exception->errors;
        }

        if (YII_DEBUG) {
            $data['debug'] = [
                'type' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'stack-trace' => explode("\n", $exception->getTraceAsString()),
            ];
        }

        $response->statusCode = $code;
        $response->data = $data;

        $response->send();
        return;
    }
}