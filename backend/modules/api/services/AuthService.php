<?php

namespace backend\modules\api\services;

use backend\modules\api\exceptions\RetryLaterException;
use backend\modules\api\forms\LoginForm;
use backend\modules\api\forms\PasswordResetRequestForm;
use backend\modules\api\forms\ResetPasswordForm;
use common\entities\User;
use common\repositories\UserRepository;
use yii\mail\MailerInterface;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class AuthService
{
    public function __construct(
        private UserRepository $users, 
        private MailerInterface $mailer, 
        private string $frontendUrl 
    ) {
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsername($form->username);

        if (!$user || !$user->validatePassword($form->password)) {
            throw new UnauthorizedHttpException('Incorrect username or password ');
        }

        $user->generateAccessToken();
        $this->users->save($user);

        return $user;
    }

    public function logout(?User $user): void
    {
        if (!$user) {
            throw new UnauthorizedHttpException('Not logged in.');
        }

        $user->access_token = null;
        $this->users->save($user);
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