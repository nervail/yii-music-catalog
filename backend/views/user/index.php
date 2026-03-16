<?php

use common\models\User;
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
            'value' => function($model) {
                return $model->getStatusName();
            }
        ],
        [
            'attribute' => 'role',
            'format' => 'raw',
            'value' => function($model) {
                $role = $model->role;
                $badgeClass = User::getRoleBadgeClass($role);

                return Html::tag('span', Html::encode($role), ['class' => $badgeClass]);
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
        ['class' => 'yii\grid\ActionColumn'],
    ]
]) ?>
