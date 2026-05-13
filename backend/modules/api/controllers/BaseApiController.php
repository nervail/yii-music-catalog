<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\ApiResponseTrait;
use backend\modules\api\exceptions\ValidationException;
use InvalidArgumentException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;

class BaseApiController extends Controller
{
    use ApiResponseTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
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