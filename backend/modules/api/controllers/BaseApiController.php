<?php

namespace backend\modules\api\controllers;

use backend\modules\api\exceptions\ValidationException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Serializer;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\filters\Cors;

class BaseApiController extends Controller
{
    public $serializer = [
        'class' => Serializer::class,
        'expandParam' => 'expand',
        'fieldsParam' => 'fields',
        
        // Если надо помещать коллекцию в обертку, то указать имя ключа, например 'items',
        'collectionEnvelope' => null,
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

	$verbFilter = $behaviors['verbFilter'];
	unset($behaviors['verbFilter']);

    $behaviors['corsFilter'] = [
        'class' => Cors::class,
        'cors' => [
            'Origin' => [$_ENV['FRONTEND_URL']],
            'Access-Control-Request-Method' => [
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE',
                'OPTIONS',
            ],
            'Access-Control-Request-Headers' => ['*'],
            'Access-Control-Allow-Credentials' => true,
            'Access-Control-Max-Age' => 86400,
        ],
    ];

	$behaviors['verbFilter'] = $verbFilter;

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    protected function success($data = [], $code = 200)
    {
        \Yii::$app->response->statusCode = $code;
        return [
            'success' => true,
            'data' => $data,
            'errors' => null,
        ];
    }

    public function findModel(
        int $id,
        string $modelClass,
        array $conditions = []
    ) {
        if (!is_subclass_of($modelClass, ActiveRecordInterface::class)) {
            \Yii::error("Class $modelClass must implement ActiveRecord interface");
            throw new UnprocessableEntityHttpException("Error while handling request");
        }

        $query = $modelClass::find()->where(['id' => $id]);

        if ($conditions) {
            $query->andWhere($conditions);
        }

        if (($model = $query->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Object not found');
    }

    public function errorIfInvalid($model)
    {
        if (!$model instanceof Model) {
            \Yii::error('Argument must be an instance of yii\\base\\Model');
            throw new UnprocessableEntityHttpException("Error while handling request");
        }
        
        if ($model->hasErrors()) {
            throw new ValidationException($model->getErrors());
        }
    }
}
