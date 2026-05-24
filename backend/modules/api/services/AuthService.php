<?php

namespace backend\modules\api\services;

use backend\modules\api\forms\LoginForm;
use common\entities\User;
use common\repositories\UserRepository;
use yii\mail\MailerInterface;
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
}