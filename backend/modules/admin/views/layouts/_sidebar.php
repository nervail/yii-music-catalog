<?php

use backend\widgets\SidebarMenu;
use common\entities\User;
use yii\helpers\Html;

$user = Yii::$app->user->identity;
$role = $user->role;

?>

<style>
    .sidebar-user-info {
        padding: 8px 12px;
    }

    .sidebar-user-info .username {
        display: block;
        color: #fff;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .sidebar-user-info .role {
        display: inline-block;
        font-size: 11px;
        line-height: 1.4;
        padding: 3px 7px;
    }
</style>

<div class="sidebar">

    <div class="user-panel mt-3 pb-3 mb-3">
        <div class="sidebar-user-info">

            <span class="username">
                <?= Html::encode($user->username) ?>
            </span>

            <span class="role <?= User::getRoleBadgeClass($role) ?> mb-2">
                <?= strtoupper($role) ?>
            </span>

            <?php if (!\Yii::$app->user->isGuest): ?>
                <?= Html::beginForm(['/admin/site/logout'], 'post', [
                    'class' => 'mt-2'
                ]) ?>

                <button class="btn btn-danger btn-sm btn-block">
                    <i class="fas fa-sign-out-alt mr-1"></i>
                    Logout
                </button>

                <?= Html::endForm() ?>
            <?php endif; ?>

        </div>
    </div>

    <nav class="mt-2">

        <?= SidebarMenu::widget([
            'items' => [

                [
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-th',
                    'url' => ['/admin/site/index'],
                ],

                // РАЗДЕЛ КАТАЛОГ (Виден админам и модераторам)
                [
                    'label' => 'Catalog',
                    'icon' => 'fas fa-tachometer-alt',
                    'visible' => \Yii::$app->user->can('moderator'),
                    'items' => [

                        [
                            'label' => 'Albums',
                            'icon' => 'fas fa-compact-disc',
                            'url' => ['/admin/album/index'],
                        ],

                        [
                            'label' => 'Artists',
                            'icon' => 'fas fa-microphone',
                            'url' => ['/admin/artist/index'],
                        ],

                        [
                            'label' => 'Genres',
                            'icon' => 'fas fa-music',
                            'url' => ['/admin/genre/index'],
                        ],

                        [
                            'label' => 'Tracks',
                            'icon' => 'fas fa-play-circle',
                            'url' => ['/admin/item/index'],
                        ],

                    ],
                ],

                // РАЗДЕЛ УПРАВЛЕНИЯ (Только для админов)
                [
                    'label' => 'User Management',
                    'icon' => 'fas fa-users-cog',
                    'url' => ['/admin/user/index'],
                    'visible' => \Yii::$app->user->can('admin'),
                ],

            ],
        ]) ?>

    </nav>

</div>