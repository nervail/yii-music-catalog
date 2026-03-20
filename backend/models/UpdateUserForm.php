<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class UpdateUserForm extends Model
{
    public $username;
    public $email;
    public $status;
    public $role;

    private $_user;

    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->status = $user->status;

        $roles = \Yii::$app->authManager->getRolesByUser($user->id);
        $this->role = !empty($roles) ? array_keys($roles)[0] : null;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['username', 'email', 'status', 'role'], 'required'],
            ['email', 'email'],
            ['status', 'integer'],
            ['role', 'in', 'range' => array_keys(Yii::$app->authManager->getRoles())],
            [['username', 'email', 'status', 'role'], 'validateCanUpdate']
        ];
    }

    public function validateCanUpdate($attribute, $params, $validator, $current)
    {
        $currentUser = \Yii::$app->user;
        $targetUser = $this->_user;

        if ($targetUser->id == $currentUser->id) {
            $this->addError($attribute, 'Вы не можете редактировать свои данные через этот раздел.');
            return;
        }

        if ($currentUser->can('superAdmin')) { // Супер админу проверки не нужны кроме редактирования себя
            return;
        }

        if ($targetUser->isAdmin()) {
            $this->addError($attribute, 'Редактирование администраторов ограничено.');
            return;
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        return User::updateWithRole($this->_user, $this->getAttributes(['username', 'email', 'status']), $this->role);
    }
}
