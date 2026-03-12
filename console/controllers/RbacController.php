<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        
        $user = $auth->createRole('user');
        $auth->add($user);

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);

        $auth->addChild($admin, $user);
        $auth->addChild($admin, $moderator);
        $auth->addChild($moderator, $user);

        $auth->assign($admin, 21);
        echo "Roles created: admin, user, moderator\n";
    }
}