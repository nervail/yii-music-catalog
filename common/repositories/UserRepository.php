<?php

namespace common\repositories;

use backend\modules\api\exceptions\RetryLaterException;
use common\entities\User;

class UserRepository
{
    /**
     * Finds user by username
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username): ?User 
    {
        return User::findOne(['username' => $username, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * Finds active user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findActiveByEmail(string $email): ?User 
    {
        return User::findOne(['email' => $email, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * Finds inactive user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findInactiveByEmail(string $email): ?User 
    {
        return User::findOne(['email' => $email, 'status' => User::STATUS_INACTIVE]);
    }

    public function findByPasswordResetToken(string $token): ?User
    {
        if (!User::isPasswordResetTokenValid($token)) {
            return null;
        }

        return User::findOne([
            'password_reset_token' => $token,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return User|null
     */
    public function findByVerificationToken(string $token): ?User
    {
        return User::findOne([
            'verification_token' => $token,
            'status' => User::STATUS_INACTIVE
        ]);
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new RetryLaterException(RetryLaterException::CODE_DEFAULT);
        }
    }
}