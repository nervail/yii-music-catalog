<?php

namespace backend\modules\api\controllers;

use backend\behaviors\RateLimitBehavior;
use backend\modules\api\controllers\BaseApiController;
use backend\modules\api\forms\LoginForm;
use backend\modules\api\services\AuthService;
use common\entities\User;

/**
 * Site controller
 */
class AuthController extends BaseApiController
{

    public function __construct(
        $id, 
        $module, 
        private AuthService $authService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = [
            'login',
        ];

        $behaviors['rateLimiter'] = [
            'class' => RateLimitBehavior::class,

            'actions' => [
                'login' => [
                    'ip' => [
                        'limit' => 5,
                        'window' => 60,
                        'identifier' => fn() =>
                            \Yii::$app->request->getRemoteIP(),
                    ],

                    'username' => [
                        'limit' => 5,
                        'window' => 60,
                        'identifier' => function () {
                            $body = \Yii::$app->request->getBodyParams();

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

    public function actionMe()
    {
        $user = \Yii::$app->user->identity;
        return $this->success([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'status' => $user->status,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $form = new LoginForm();

        if ($form->load(\Yii::$app->request->post(), '') && $form->validate()) {
            
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
        $user = \Yii::$app->user->identity;

        $this->authService->logout($user);

        return $this->success([
            'message' => 'Logged out successfully.',
        ]);
    }
}
