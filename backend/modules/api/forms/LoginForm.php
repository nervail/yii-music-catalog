<?php

namespace backend\modules\api\forms;

use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public string|null $username = null;
    public string|null $password = null;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required and string
            [['username', 'password'], 'required'],
            [['username', 'password'], 'trim'],
            [['username', 'password'], 'string'],
        ];
    }
}
