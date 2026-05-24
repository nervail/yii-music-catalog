<?php

namespace backend\modules\api\services;

use backend\modules\api\exceptions\RetryLaterException;
use backend\modules\api\forms\PasswordResetRequestForm;
use backend\modules\api\forms\ResetPasswordForm;
use common\entities\User;
use common\repositories\UserRepository;
use yii\mail\MailerInterface;
use yii\web\BadRequestHttpException;

class PasswordResetService
{
    public function __construct(
        private UserRepository $users, 
        private MailerInterface $mailer, 
        private string $frontendUrl 
    ) {
    }

    public function requestPasswordReset(PasswordResetRequestForm $form): void
    {
        $user = $this->users->findActiveByEmail($form->email);

        if (!$user) {
            return;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            $this->users->save($user);
        }

        if (!$this->sendEmailPasswordReset($user)) {
            throw new RetryLaterException(RetryLaterException::CODE_DEFAULT);
        }
    }

    private function sendEmailPasswordReset(User $user)
    {
        $resetLink = $this->frontendUrl . '/reset-password?token=' . $user->password_reset_token; 

        return $this
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                [
                    'user' => $user,
                    'resetLink' => $resetLink,
                ]
            )
            ->setTo($user->email)
            ->setSubject('Password reset for ' . \Yii::$app->name)
            ->send();
    }

    public function passwordReset(ResetPasswordForm $form): void
    {
        $user = $this->users->findByPasswordResetToken($form->token);

        if (!$user) {
            throw new BadRequestHttpException("Token expired");
        }

        $user->setPassword($form->password);
        $user->removePasswordResetToken();
        $user->access_token = null;
        
        $this->users->save($user);
    }
}