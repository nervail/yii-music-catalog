<?php

namespace backend\modules\api\services;

use backend\modules\api\forms\SignupForm;
use common\entities\User;
use yii\web\ServerErrorHttpException;

class SignupService
{
    public function signup(SignupForm $form): User
    {
        $user = User::create($form->username, $form->email, $form->password);


        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!$user->save()) {
                \Yii::error('User save error: ' . json_encode($user->getErrors()), 'api-registration');
                throw new ServerErrorHttpException('Saving error.');
            }
    
            $auth = \Yii::$app->authManager;
            $role = $auth->getRole(User::ROLE_USER);
    
            if ($role) {
                $auth->assign($role, $user->id);
            } else {
                \Yii::error('User save error: ' . 'Default role not found', 'api-registration');
                throw new ServerErrorHttpException();
            }
            
            if (!$this->sendEmail($user)) {
                throw new ServerErrorHttpException('Sending email error.');
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $user;
    }

    private function sendEmail(User $user)
    {
        return \Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Account registration at ' . \Yii::$app->name)
            ->send();
    }
}
