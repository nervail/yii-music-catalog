<?php

namespace frontend\components\api;

use yii\data\DataProviderInterface;
use yii\rest\Serializer;

trait ApiResponseTrait
{
    protected function success($data = [], $code = 200)
    {
        \Yii::$app->response->statusCode = $code;
        return [
            'success' => true,
            'data' => $data,
        ];
    }

    /**
     * Форматирует данные из DataProvider для ответа
     * @param DataProviderInterface $dataProvider
     * @return array
     */
    protected function prepareResource(DataProviderInterface $dataProvider, string $keyName)
    {
        $serializer = new Serializer([
            'collectionEnvelope' => $keyName,
        ]);

        return $serializer->serialize($dataProvider);
    }
}