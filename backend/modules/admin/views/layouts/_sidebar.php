<?php
use backend\widgets\SidebarMenu;
use common\entities\User;
use yii\helpers\Html;

$user = Yii::$app->user->identity;
$role = $user->role;
?>

<div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3">
        <div class="image">
            <img src="/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info ml-2">
            <a class="d-block"><?= Html::encode($user->username) ?></a>
            <span class="<?= User::getRoleBadgeClass($role) ?>"><?= strtoupper($role) ?></span>
        </div>
        <?php if (!\Yii::$app->user->isGuest): ?>

            <?= \yii\helpers\Html::beginForm(['/admin/site/logout'], 'post', [
                'class' => 'mt-2'
            ]) ?>

            <button class="btn btn-danger btn-sm btn-block">
                <i class="fas fa-sign-out-alt mr-1"></i>
                Logout
            </button>

            <?= \yii\helpers\Html::endForm() ?>

        <?php endif; ?>
    </div>

    <nav class="mt-2">
        <?= SidebarMenu::widget([
            'items' => [
                ['label' => 'Dashboard', 'icon' => 'fas fa-th', 'url' => ['/admin/site/index']],
                
                // РАЗДЕЛ КАТАЛОГ (Виден админам и модераторам)
                [
                    'label' => 'Catalog',
                    'icon' => 'fas fa-tachometer-alt',
                    'visible' => Yii::$app->user->can('moderator'),
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
                    'visible' => Yii::$app->user->can('admin'),
                ],
            ],
        ]) ?>
    </nav>
</div>