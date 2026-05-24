<?php

namespace backend\modules\api\controllers;

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
                'access_token' => $user->access_token,
                'username' => $user->username,
            ]);
        }

        $this->errorIfInvalid($form);
    }
}
