<?php

namespace backend\modules\api\controllers;

use backend\modules\api\controllers\BaseApiController;
use backend\modules\api\forms\LoginForm;
use backend\modules\api\forms\PasswordResetRequestForm;
use backend\modules\api\forms\ResendVerificationEmailForm;
use backend\modules\api\forms\ResetPasswordForm;
use backend\modules\api\forms\SignupForm;
use backend\modules\api\forms\VerifyEmailForm;
use backend\modules\api\services\AuthService;
use backend\modules\api\services\SignupService;
use backend\modules\api\services\VerifyService;
use common\entities\User;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Site controller
 */
class SiteController extends BaseApiController
{

    public function __construct(
        $id, 
        $module, 
        private SignupService $signupService, 
        private AuthService $authService,
        private VerifyService $verifyService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = [
            'index', 
            'signup', 
            'login', 
            'request-password-reset', 
            'reset-password', 
            'check-reset-token', 
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
            $user = $this->signupService->signup($form);

            return $this->success([
                'message' => 'Signed up successfully. Please check your email to verify your account.',
                'access_token' => $user->access_token,
                'username' => $user->username,
            ]);
        }

        $this->errorIfInvalid($form);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $form = new LoginForm();

        if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
            
            $user = $this->authService->auth($form);

            return $this->success([
                'access_token' => $user->access_token,
                'username' => $user->username,
            ]);
        }

        $this->errorIfInvalid($form);
    }

    public function actionLogout()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $this->authService->logout($user);

        return $this->success([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $form = new PasswordResetRequestForm();

        if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
            
            $this->authService->requestPasswordReset($form);

            return $this->success([
                'message' => 'If such email exists, we have sent a mail.'
            ]);
        }

        $this->errorIfInvalid($form);
    }

    /**
     * Resets password.
     *
     * @return mixed
     * @throws UnauthorizedHttpException
     */
    public function actionResetPassword()
    {
        $form = new ResetPasswordForm();

        if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
            $this->authService->passwordReset($form);

            return $this->success([
                'message' => 'New password saved.',
            ]);
        }

        $this->errorIfInvalid($form);
    }

    public function actionCheckResetToken()
    {
        $token = \Yii::$app->request->post('token');

        if (!User::isPasswordResetTokenValid($token)) {
            throw new UnprocessableEntityHttpException('Token expired');
        }

        return $this->success(['valid' => true]);
    }

    /**
     * Verify email address
     *
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail()
    {
        $form = new VerifyEmailForm();
        
        if ($form->load(\Yii::$app->request->post(), '') && $form->validate()) {
            $this->verifyService->verifyEmail($form);
            
            return $this->success([
                'message' => 'Email verified!'
            ]);
        }

        $this->errorIfInvalid($form);
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $form = new ResendVerificationEmailForm();
        if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
            $this->verifyService->resendVerificationEmail($form);
            
            return $this->success([
                'message' => 'If such email exists, we have sent a mail.'
            ]);
        }

        $this->errorIfInvalid($form);
    }
}
