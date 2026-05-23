<?php

namespace backend\modules\api\forms;

use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password = null;
    public $token = null;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token', 'password'], 'required'],
            [['token'], 'string'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();

        return $user->save(false);
    }
}
