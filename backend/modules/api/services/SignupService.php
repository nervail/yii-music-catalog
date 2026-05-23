<?php

namespace backend\modules\api\services;

use backend\modules\api\exceptions\RetryLaterException;
use backend\modules\api\forms\SignupForm;
use common\entities\User;
use common\repositories\UserRepository;
use yii\db\Connection;
use yii\rbac\ManagerInterface;

class SignupService
{
    public function __construct(
        private Connection $db,
        private ManagerInterface $authManager,
        private UserRepository $users,
        private VerifyService $verifyService
    ) {
    }

    public function signup(SignupForm $form): User
    {
        $user = User::create($form->username, $form->email, $form->password);

        $transaction = $this->db->beginTransaction();
        try {
            $this->users->save($user);
    
            $role = $this->authManager->getRole(User::ROLE_USER);

            if (!$role) {
                \Yii::error('User save error: Default role not found', 'api-registration');
                throw new RetryLaterException(RetryLaterException::CODE_REG_UNAVAILABLE);
            }

            $this->authManager->assign($role, $user->id);
            
            if (!$this->verifyService->sendVerificationEmail($user)) {
                \Yii::error("Email sending failed for user ID: {$user->id}", 'api-registration');
                throw new RetryLaterException(RetryLaterException::CODE_MAIL_FAILED);
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();

            if ($e instanceof \yii\web\HttpException) {
                throw $e;
            }

            \Yii::error('Критический сбой регистрации: ' . $e->getMessage(), 'api-registration');
            throw new RetryLaterException(RetryLaterException::CODE_SYSTEM_ERROR, $e);
        }

        return $user;
    }
}
