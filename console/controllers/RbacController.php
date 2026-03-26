<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        if (!YII_ENV_DEV) {
            throw new \yii\base\InvalidConfigException('Cannot removeAll RBAC in production!');
        }   

        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // MODERATOR + ADMIN PERMISSIONS

        $indexItem = $auth->createPermission('indexItem');
        $auth->add($indexItem);
        $viewItem = $auth->createPermission('viewItem');
        $auth->add($viewItem);
        $createItem = $auth->createPermission('createItem');
        $auth->add($createItem);
        $updateItem = $auth->createPermission('updateItem');
        $auth->add($updateItem);
        $deleteItem = $auth->createPermission('deleteItem');
        $auth->add($deleteItem);

        $indexArtist = $auth->createPermission('indexArtist');
        $auth->add($indexArtist);
        $viewArtist = $auth->createPermission('viewArtist');
        $auth->add($viewArtist);
        $createArtist = $auth->createPermission('createArtist');
        $auth->add($createArtist);
        $updateArtist = $auth->createPermission('updateArtist');
        $auth->add($updateArtist);
        $deleteArtist = $auth->createPermission('deleteArtist');
        $auth->add($deleteArtist);

        $indexGenre = $auth->createPermission('indexGenre');
        $auth->add($indexGenre);
        $viewGenre = $auth->createPermission('viewGenre');
        $auth->add($viewGenre);
        $createGenre = $auth->createPermission('createGenre');
        $auth->add($createGenre);
        $updateGenre = $auth->createPermission('updateGenre');
        $auth->add($updateGenre);
        $deleteGenre = $auth->createPermission('deleteGenre');
        $auth->add($deleteGenre);
        // END MODERATOR + ADMIN PERMISSIONS

        // ADMIN (ONLY) PERMISSIONS
        $banUser = $auth->createPermission('banUser');
        $auth->add($banUser);
        $restoreUser = $auth->createPermission('restoreUser');
        $auth->add($restoreUser);
        $archiveUser = $auth->createPermission('archiveUser');
        $auth->add($archiveUser);
        $updateUser = $auth->createPermission('updateUser');
        $auth->add($updateUser);
        $assignRole = $auth->createPermission('assignRole');
        $auth->add($assignRole);
        $viewUsers = $auth->createPermission('viewUsers');
        $auth->add($viewUsers);
        $viewLogs = $auth->createPermission('viewLogs');
        $auth->add($viewLogs);
        // END ADMIN (ONLY) PERMISSIONS

        $user = $auth->createRole('user');
        $auth->add($user);

        $superAdmin = $auth->createRole('superAdmin');
        $auth->add($superAdmin);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $banUser);
        $auth->addChild($admin, $restoreUser);
        $auth->addChild($admin, $archiveUser);
        $auth->addChild($admin, $assignRole);
        $auth->addChild($admin, $viewUsers);
        $auth->addChild($admin, $viewLogs);
        $auth->addChild($admin, $updateUser);

        
        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);
        $auth->addChild($moderator, $indexItem);
        $auth->addChild($moderator, $viewItem);
        $auth->addChild($moderator, $createItem);
        $auth->addChild($moderator, $updateItem);
        $auth->addChild($moderator, $deleteItem);

        $auth->addChild($moderator, $indexArtist);
        $auth->addChild($moderator, $viewArtist);
        $auth->addChild($moderator, $createArtist);
        $auth->addChild($moderator, $updateArtist);
        $auth->addChild($moderator, $deleteArtist);
        
        $auth->addChild($moderator, $indexGenre);
        $auth->addChild($moderator, $viewGenre);
        $auth->addChild($moderator, $createGenre);
        $auth->addChild($moderator, $updateGenre);
        $auth->addChild($moderator, $deleteGenre);


        $auth->addChild($moderator, $user);
        $auth->addChild($admin, $moderator);
        $auth->addChild($superAdmin, $admin);

        if (YII_ENV_DEV) {
            $superAdminUser = 20;
            $adminUser = 21;
            $moderatorUser = 22;
            $test_userUser = 23;

            $auth->assign($superAdmin, $superAdminUser);
            $auth->assign($admin, $adminUser);
            $auth->assign($moderator, $moderatorUser);
            $auth->assign($user, $test_userUser);
            echo "Roles created: admin, moderator, user\n";
            echo "Admin id=21, Moderator id=22, test_user id=23\n";
        } else {
            echo "Roles created: admin, user, moderator\n";
        }
    }
}