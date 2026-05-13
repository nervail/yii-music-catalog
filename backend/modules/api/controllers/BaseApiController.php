<?php

namespace backend\modules\api\controllers;

use backend\modules\api\exceptions\ValidationException;
use InvalidArgumentException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Serializer;
use yii\web\NotFoundHttpException;

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
        ];
    }

    public function findModel(int $id, string $modelClass)
    {
        if (!is_subclass_of($modelClass, ActiveRecordInterface::class)) {
            throw new InvalidArgumentException("Class $modelClass must implement ActiveRecord interface");
        }

        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Object not found');
    }

    public function errorIfInvalid($model)
    {
        if (!$model instanceof Model) {
            throw new InvalidArgumentException("Argument must be an instance of yii\\base\\Model");
        }
        
        if ($model->hasErrors()) {
            throw new ValidationException($model->getErrors());
        }
    }
}