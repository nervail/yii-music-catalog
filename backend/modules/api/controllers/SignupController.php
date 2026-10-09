<?php

namespace backend\modules\api\controllers;

use backend\behaviors\RateLimitBehavior;
use backend\modules\api\controllers\BaseApiController;
use backend\modules\api\forms\SignupForm;
use backend\modules\api\services\SignupService;
use Yii;

/**
 * Site controller
 */
class SignupController extends BaseApiController
{

    public function __construct(
        $id, 
        $module, 
        private SignupService $signupService, 
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = [
            'index',
        ];

        $behaviors['rateLimiter'] = [
            'class' => RateLimitBehavior::class,

            'actions' => [
                'index' => [
                    'ip' => [
                        'limit' => 3,
                        'window' => 60,
                        'identifier' => fn() =>
                            Yii::$app->request->getRemoteIP(),
                    ],

                    'email' => [
                        'limit' => 3,
                        'window' => 60,
                        'identifier' => function () {
                            $body = Yii::$app->request->getBodyParams();

                            $email = trim($body['email'] ?? '');

                            return $email !== ''
                                ? strtolower($email)
                                : null;
                        },
                    ],

                    'username' => [
                        'limit' => 3,
                        'window' => 60,
                        'identifier' => function () {
                            $body = Yii::$app->request->getBodyParams();

                            $username = trim($body['username'] ?? '');

                            return $username !== ''
                                ? strtolower($username)
                                : null;
                        },
                    ],
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
            $user = $this->signupService->signup($form);

            return $this->success([
                'message' => 'Signed up successfully. Please check your email to verify your account.',
                'username' => $user->username,
            ]);
        }

        $this->errorIfInvalid($form);
    }
}
