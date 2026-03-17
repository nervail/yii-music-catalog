<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/** @var $model common\models\User */
?>

<div class="user-view">

    <!-- КНОПКИ -->
    <p>
        <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data-method' => 'post',
            'data-confirm' => 'Ты точно хочешь удалить этого пользователя?'
        ]) ?>
    </p>

    <div class="row">
        
        <!-- ЛЕВАЯ КОЛОНКА (профиль) -->
        <div class="col-md-4">

            <div class="card card-primary card-outline">
                <div class="card-body box-profile text-center">

                    <h3 class="profile-username">
                        <?= Html::encode($model->username) ?>
                    </h3>

                    <p class="text-muted">
                        <?= Html::encode($model->email) ?>
                    </p>

                    <hr>

                    <!-- ROLE -->
                    <p>
                        <strong>Role:</strong><br>
                        <?= Html::tag('span', $model->role, [
                            'class' => $model->getRoleBadgeClass($model->role)
                        ]) ?>
                    </p>

                    <!-- STATUS -->
                    <p>
                        <strong>Status:</strong><br>
                        <?= Html::tag('span', $model->getStatusName(), [
                            'class' => $model->getStatusBadgeClass($model->status)
                        ]) ?>
                    </p>

                </div>
            </div>

        </div>

        <!-- ПРАВАЯ КОЛОНКА (детали) -->
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User details</h3>
                </div>

                <div class="card-body">

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'username',
                            'email',
                            [
                                'attribute' => 'created_at',
                                'value' => fn($model) => Yii::$app->formatter->asDatetime($model->created_at),
                            ],
                            [
                                'attribute' => 'updated_at',
                                'value' => fn($model) => Yii::$app->formatter->asDatetime($model->updated_at),
                            ],
                        ],
                    ]) ?>

                </div>
            </div>

        </div>

    </div>

</div>