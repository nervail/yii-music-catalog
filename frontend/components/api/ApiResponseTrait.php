<?php

namespace frontend\components\api;

use yii\data\DataProviderInterface;

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
        $pagination = $dataProvider->getPagination();
        
        $result = [
            $keyName => $dataProvider->getModels(),
        ];

        if ($pagination !== false) {
            $result['pagination'] = [
                'totalCount'  => (int)$dataProvider->getTotalCount(),
                'pageCount'   => (int)$pagination->getPageCount(),
                'currentPage' => (int)$pagination->getPage() + 1,
                'pageSize'    => (int)$pagination->getPageSize(),
            ];
        }

        return $result;
    }
}