<?php

namespace backend\modules\api\controllers;

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

        return $behaviors;
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
