<?php

namespace backend\modules\api\services;

use backend\modules\api\exceptions\RetryLaterException;
use backend\modules\api\forms\ResendVerificationEmailForm;
use backend\modules\api\forms\VerifyEmailForm;
use common\entities\User;
use common\repositories\UserRepository;
use yii\mail\MailerInterface;
use yii\web\BadRequestHttpException;
use yii\web\UnprocessableEntityHttpException;

class VerifyService
{
    public function __construct(
        private UserRepository $users,
        private MailerInterface $mailer,
        private string $frontendUrl,
    ) {
    }
    
    public function verifyEmail(VerifyEmailForm $form): void
    {
        $user = $this->users->findByVerificationToken($form->token);

        if (!$user) {
            throw new BadRequestHttpException("Wrong link");
        }

        if (!User::isEmailVerifyTokenValid($form->token)) {
            throw new UnprocessableEntityHttpException("Token expired");
        }

        $user->verifyEmail();
        $this->users->save($user);
    }

    public function resendVerificationEmail(ResendVerificationEmailForm $form): void
    {
        $user = $this->users->findInactiveByEmail($form->email);

        if (!$user) {
            return;
        }

        if (!User::isEmailVerifyTokenValid($user->verification_token)) {
            $user->generateEmailVerificationToken();
            $this->users->save($user);
        }
        
        if (!$this->sendVerificationEmail($user)) {
            \Yii::error("Email resending failed for user ID: {$user->id}", 'api-verify');
            throw new RetryLaterException(RetryLaterException::CODE_MAIL_FAILED);
        }
    }

    public function sendVerificationEmail(User $user): bool
    {
        $verifyLink = $this->frontendUrl . '/verify-email?token=' . $user->verification_token;

        return $this->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                [
                    'user' => $user,
                    'verifyLink' => $verifyLink,
                ]
            )
            ->setTo($user->email)
            ->setSubject('Account registration')
            ->send();
    }

}