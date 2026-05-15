<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\entities\Subscription $model */

$this->title = "Subscription #" . $model->id;
?>

<section class="content-header">
    <div class="container-fluid">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Subscription Details</h3>

                <div class="card-tools">
                    <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to force unsubscribe this user?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'attribute' => 'id',
                            'headerOptions' => ['style' => 'width: 100px'],
                        ],
                        [
                            'label' => 'User',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->user 
                                    ? Html::a(Html::encode($model->user->username), ['user/view', 'id' => $model->user_id], ['class' => 'text-dark font-weight-bold']) 
                                    : '<span class="text-muted">Unknown User</span>';
                            },
                        ],
                        [
                            'label' => 'Artist',
                            'format' => 'raw',
                            'value' => function($model) {
                                return $model->artist 
                                    ? Html::a(Html::encode($model->artist->name), ['artist/view', 'id' => $model->artist_id], [
                                        'class' => 'badge badge-info p-2'
                                    ]) 
                                    : '<span class="text-muted">Unknown Artist</span>';
                            },
                        ],
                        [
                            'label' => 'Subscribed At',
                            'value' => \Yii::$app->formatter->asDateTime($model->created_at),
                        ],
                    ],
                ]) ?>

            </div>
        </div>

    </div>
</section>