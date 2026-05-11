<?php

use common\entities\User;
use yii\grid\GridView;
use yii\helpers\Html;

?>

<h1>Users</h1>

<?= Html::beginForm(['index'], 'get', ['class' => 'form-inline mb-2']); ?>

    <?= Html::activeTextInput($searchModel, 'id', ['class' => 'form-control mr-2', 'placeholder' => 'ID']) ?>
    <?= Html::activeTextInput($searchModel, 'username', ['class' => 'form-control mr-2', 'placeholder' => 'Name']) ?>
    <?= Html::activeTextInput($searchModel, 'email', ['class' => 'form-control mr-2', 'placeholder' => 'Description']) ?>
    <?= Html::activeDropDownList($searchModel, 'status', User::getStatusList(), ['class' => 'form-control mr-2', 'prompt' => 'Choose a status']) ?>
    <?= Html::activeDropDownList($searchModel, 'role', User::getRoleList(), ['class' => 'form-control mr-2', 'prompt' => 'Choose a role']) ?>
    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Reset', ['index'], ['class' => 'btn btn-default ml-2']) ?>

<?= Html::endForm(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'username',
        'email',
        [
            'attribute' => 'status',
            'format' => 'raw',
            'value' => function($model) {
                $status = $model->getStatusName();
                $statusBadgeClass = $model->getStatusBadgeClass($model->status);

                return Html::tag('span', Html::encode($status), ['class' => $statusBadgeClass]);
            }
        ],
        [
            'attribute' => 'role',
            'format' => 'raw',
            'value' => function($model) {
                $role = $model->role;
                $roleBadgeClass = User::getRoleBadgeClass($role);

                return Html::tag('span', Html::encode($role), ['class' => $roleBadgeClass]);
            },
            'filter' => [
                'admin' => 'Admin',
                'moderator' => 'Moderator',
                'user' => 'User',
            ],
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return Yii::$app->formatter->asDateTime($model->created_at);
            }
        ],
        [
            'attribute' => 'updated_at',
            'value' => function ($model) {
                return Yii::$app->formatter->asDateTime($model->updated_at);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            // Добавляем наши кастомные кнопки в шаблон
            'template' => '{view} {update} {delete} {ban} {restore}',
            'buttons' => [
                'ban' => function ($url, $model) {
                    return Html::a('<i class="fas fa-solid fa-hammer"></i>', ['ban', 'id' => $model->id], [
                        'title' => 'Ban',
                        'aria-label' => 'Ban',
                        'data-confirm' => 'Вы уверены, что хотите забанить этого пользователя?',
                        'data-method' => 'post',
                        'class' => 'text-danger',
                    ]);
                },
                'restore' => function ($url, $model) {
                    return Html::a('<i class="fas fa-trash-restore"></i>', ['restore', 'id' => $model->id], [
                        'title' => 'Restore',
                        'aria-label' => 'Restore',
                        'data-confirm' => 'Восстановить пользователя?',
                        'data-method' => 'post',
                        'class' => 'text-success',
                    ]);
                },
                'delete' => function ($url, $model){
                    return Html::a('<span class="fas fa-trash"></span>', ['delete', 'id' => $model->id], [
                        'title' => 'Delete',
                        'aria-label' => 'Delete',
                        'data-confirm' => 'Уверены что хотите удалить этого пользователя?',
                        'data-method' => 'post',
                        'class' => 'text-danger', // КРАСНЫЙ ЦВЕТ ДЛЯ КОРЗИНЫ
                    ]);
                }
            ],
            'visibleButtons' => [
                // Удалить можно только если он еще не удален (не в архиве)
                'delete' => function ($model) {
                    return $model->status !== User::STATUS_DELETED;
                },
                // Забанить можно только если он еще не в бане и не удален
                'ban' => function ($model) {
                    return $model->status !== User::STATUS_BANNED && $model->status !== User::STATUS_DELETED;
                },
                // Восстановить можно только если он в бане или удален 
                // (условие: не активен и не "ожидает подтверждения")
                'restore' => function ($model) {
                    return $model->status === User::STATUS_BANNED || $model->status === User::STATUS_DELETED;
                },
            ],
        ],
    ]
]) ?>
