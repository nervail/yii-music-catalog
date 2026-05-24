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
use backend\modules\api\services\PasswordResetService;
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
class ResetController extends BaseApiController
{

    public function __construct(
        $id, 
        $module, 
        private PasswordResetService $passwordResetService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = [
            'request-password-reset', 
            'reset-password', 
            'check-reset-token',
        ]; 

        return $behaviors;
    }

    public function actionRequestPasswordReset()
    {
        $form = new PasswordResetRequestForm();

        if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
            
            $this->passwordResetService->requestPasswordReset($form);

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
            $this->passwordResetService->passwordReset($form);

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
}
