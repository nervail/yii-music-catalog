<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\modules\admin\search\SubscriptionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Subscriptions Management';
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="col-sm-6 text-right">
                </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <?php // Форма поиска ?>
        <?= $this->render('_search', ['searchModel' => $searchModel]) ?>

        <div class="card">
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table table-hover table-valign-middle mb-0'],
                    'summary' => false,
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'headerOptions' => ['style' => 'width: 70px'],
                        ],
                        [
                            'attribute' => 'user_id',
                            'label' => 'User',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->user 
                                    ? Html::a(Html::encode($model->user->username), ['user/view', 'id' => $model->user_id], ['class' => 'text-dark font-weight-bold']) 
                                    : '<span class="text-muted">Unknown User</span>';
                            }
                        ],
                        [
                            'attribute' => 'artist_id',
                            'label' => 'Artist',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->artist 
                                    ? Html::a(Html::encode($model->artist->name), ['artist/view', 'id' => $model->artist_id], ['class' => 'badge badge-info p-2']) 
                                    : '<span class="text-muted">Unknown Artist</span>';
                            }
                        ],
                        [
                            'attribute' => 'created_at',
                            'label' => 'Subscribed At',
                            'format' => ['date', 'medium'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'headerOptions' => ['style' => 'width: 100px'],
                            'template' => '{view} {delete}',
                            'buttons' => [
                                'view' => function ($url) {
                                    return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-xs btn-default text-primary']);
                                },
                                'delete' => function ($url) {
                                    return Html::a('<i class="fas fa-trash"></i>', $url, [
                                        'class' => 'btn btn-xs btn-default text-danger',
                                        'data-method' => 'post',
                                        'data-confirm' => 'Are you sure you want to force unsubscribe this user?',
                                    ]);
                                },
                            ],
                        ],
                    ]
                ]) ?>
            </div>
        </div>

    </div>
</section>