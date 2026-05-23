<?php

namespace backend\modules\api\forms;

use yii\base\Model;

class VerifyEmailForm extends Model
{
    public $token = null;

    public function rules()
    {
        return [
            [['token'], 'required'],
            [['token'], 'string'],
        ];
    }
}
