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

        // Создание ролей модератора
        $entities = ['Item', 'Artist', 'Genre'];
        $actions = ['index', 'view', 'create', 'update', 'delete'];

        $moderatorPermissions = [];

        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                $name = $action . $entity;
                $permission = $auth->createPermission($name);
                $auth->add($permission);
                $moderatorPermissions[] = $permission;
            }
        }

        // Создание ролей админа
        $adminActionNames = [
            'banUser', 'restoreUser', 'archiveUser', 'updateUser', 'viewUsers', 'viewLogs'
        ];

        $adminPermissions = [];

        foreach ($adminActionNames as $name) {
            $permission = $auth->createPermission($name);
            $auth->add($permission);
            $adminPermissions[] = $permission;
        }

        // Роли
        $superAdmin = $auth->createRole('superAdmin');
        $auth->add($superAdmin);

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);
   
        $user = $auth->createRole('user');
        $auth->add($user);

        // Назначение разрешений для ролей
        foreach ($moderatorPermissions as $perm) {
            $auth->addChild($moderator, $perm);
        }

        foreach ($adminPermissions as $perm) {
            $auth->addChild($admin, $perm);
        }

        // Иерархия ролей
        $auth->addChild($superAdmin, $admin);
        $auth->addChild($admin, $moderator);
        $auth->addChild($moderator, $user);

        if (YII_ENV_DEV) {
            $assignments = [
                'superAdmin' => 20,
                'admin'      => 21,
                'moderator'  => 22,
                'user'       => 23,
            ];

            foreach ($assignments as $roleName => $userId) {
                $role = $auth->getRole($roleName);
                if ($role) {
                    $auth->assign($role, $userId);
                }
            }
            
            echo "RBAC initialized successfully (DEV mode).\n";
            echo "Assignments: SuperAdmin(20), Admin(21), Moderator(22), User(23)\n";
        }
    }
}