<?php

namespace backend\modules\api\controllers;

use backend\modules\api\forms\ResendVerificationEmailForm;
use backend\modules\api\forms\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use backend\modules\api\controllers\BaseApiController;
use common\forms\LoginForm;
use backend\modules\api\forms\PasswordResetRequestForm;
use backend\modules\api\forms\ResetPasswordForm;
use backend\modules\api\forms\SignupForm;
use backend\modules\api\services\SignupService;
use common\entities\User;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * Site controller
 */
class SiteController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = [
            'index', 
            'signup', 
            'login', 
            'request-password-reset', 
            'reset-password', 
            'verify-email',
            'resend-verification-email',
        ]; 

        return $behaviors;
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->success([
            'status' => 'OK',
            'version' => '1.0.0',
            'message' => 'Music Catalog API',
            'time' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
            $user = (new SignupService())->signup($form);

            return $this->success([
                'message' => 'Signed up successfully. Please check your email to verify your account.',
                'access_token' => $user->access_token,
                'username' => $user->username,
            ]);
        }

        $this->errorIfInvalid($form);
        throw new BadRequestHttpException('Incorrect data.');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {

            /** @var User $user */
            $user = \Yii::$app->user->identity;

            $user->generateAccessToken();
            $user->save();

            return $this->success([
                'access_token' => $user->access_token,
                'username' => $user->username,
            ]);
        }

        throw new UnauthorizedHttpException('Incorrect username or password.');
    }

    public function actionLogout()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        if (!$user) {
            throw new UnauthorizedHttpException('Not logged in.');
        }

        $user->access_token = null;
        $user->save(false);

        Yii::$app->user->logout();

        return $this->success([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if (!$model->load(Yii::$app->request->post(), '')) {
            throw new BadRequestHttpException('No data provided.');
        }

        if (!$model->validate()) {
            $this->errorIfInvalid($model);
            throw new BadRequestHttpException('Incorrect data.');
        }

        if (!$model->sendEmail()) {
            throw new ServerErrorHttpException('Failed to send email.');
        }

        return $this->success([
            'message' => 'Instructions sent to your email.'
        ]);
    }
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post(), '') && $model->validate() && $model->resetPassword()) {
            return $this->success([
                'message' => 'New password saved.',
            ]);
        }

        $this->errorIfInvalid($model);

        throw new BadRequestHttpException('Incorrect data.');
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->verifyEmail()) {
            return $this->success([
                'message' => 'Email verified!'
            ]);
        }

        throw new BadRequestHttpException('Failed to verify email.');
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            if ($model->sendEmail()) {
                return $this->success([
                    'message' => 'Check your email for further instructions.'
                ]);
            }

            throw new BadRequestHttpException('Sorry, we are unable to resend verification email for the provided email address.');
        }

        $this->errorIfInvalid($model);
        throw new BadRequestHttpException('Incorrect data.');
    }
}
