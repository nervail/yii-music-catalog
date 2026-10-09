<?php

namespace backend\modules\api\controllers;

use backend\behaviors\RateLimitBehavior;
use backend\modules\api\controllers\BaseApiController;
use backend\modules\api\forms\PasswordResetRequestForm;
use backend\modules\api\forms\ResetPasswordForm;
use backend\modules\api\services\PasswordResetService;
use common\entities\User;
use Yii;
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

        $behaviors['rateLimiter'] = [
            'class' => RateLimitBehavior::class,

            'actions' => [
                'request-password-reset' => [
                    'ip' => [
                        'limit' => 3,
                        'window' => 60,
                        'identifier' => fn() =>
                            \Yii::$app->request->getRemoteIP(),
                    ],

                    'email' => [
                        'limit' => 3,
                        'window' => 60,
                        'identifier' => function () {
                            $body = \Yii::$app->request->getBodyParams();

                            $email = trim($body['email'] ?? '');

                            return $email !== ''
                                ? strtolower($email)
                                : null;
                        },
                    ],
                ],

                'check-reset-token' => [
                    'ip' => [
                        'limit' => 10,
                        'window' => 60,
                        'identifier' => fn() =>
                            \Yii::$app->request->getRemoteIP(),
                    ],
                ],

                'reset-password' => [
                    'ip' => [
                        'limit' => 5,
                        'window' => 60,
                        'identifier' => fn() =>
                            \Yii::$app->request->getRemoteIP(),
                    ],
                ],
            ],
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
