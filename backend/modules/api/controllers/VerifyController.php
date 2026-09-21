<?php

namespace backend\modules\api\controllers;

use backend\modules\api\controllers\BaseApiController;
use backend\modules\api\forms\ResendVerificationEmailForm;
use backend\modules\api\forms\VerifyEmailForm;
use backend\modules\api\services\VerifyService;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\TooManyRequestsHttpException;

/**
 * Site controller
 */
class VerifyController extends BaseApiController
{

    public function __construct(
        $id, 
        $module, 
        private VerifyService $verifyService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = [
            'verify-email',
            'resend-verification-email',
        ]; 

        return $behaviors;
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

            $cacheKey = 'resend-verification:' . hash(
                'sha256',
                strtolower(trim($form->email))
            );

            if (Yii::$app->cache->get($cacheKey)) {
                throw new TooManyRequestsHttpException(
                    'Please wait before requesting another email.'
                );
            }

            $this->verifyService->resendVerificationEmail($form);

            Yii::$app->cache->set($cacheKey, true, 60);

            return $this->success([
                'message' => 'If such email exists, we have sent a mail.'
            ]);
        }

        $this->errorIfInvalid($form);
    }
}
