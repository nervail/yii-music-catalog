<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\entities\Album $model */
/** @var yii\data\ActiveDataProvider $trackProvider */

?>
<section class="content-header">
    <div class="container-fluid">
        <h1><?= Html::encode($model->name) ?></h1>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Album Details</h3>

                <div class="card-tools">
                    <?= Html::a('Update', ['update', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-primary'
                    ]) ?>

                    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'name',
                        [
                            'label' => 'Cover',
                            'format' => ['image', ['style' => 'max-width: 300px; height: auto;', 'class' => 'img-thumbnail']],
                            'value' => function ($model) {
                                return $model->getImageLink();
                            },
                        ],
                        [
                            'label' => 'Artist',
                            'format' => 'raw',
                            'value' => function($model) {
                                $safeName = Html::encode($model->artist->name);
                                return Html::a($safeName, ['artist/view', 'id' => $model->artist_id], [
                                    'class' => 'text-primary font-weight-bold'
                                ]);
                            },
                        ],
                        [
                            'label' => 'created_at',
                            'value' => \Yii::$app->formatter->asDateTime($model->created_at),
                        ],
                        [
                            'label' => 'updated_at',
                            'value' => \Yii::$app->formatter->asDateTime($model->updated_at),
                        ],
                    ],
                ]) ?>

            </div>

        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Tracks in this Album</h3>
                <div class="card-tools">
                    <?= Html::a('<i class="fas fa-plus"></i> Add Track', ['item/create', 'album_id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                </div>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $trackProvider,
                    'summary' => false,
                    'tableOptions' => ['class' => 'table table-striped table-valign-middle mb-0'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'width: 50px;'],
                        ],
                        [
                            'attribute' => 'name',
                            'format' => 'raw',
                            'value' => function ($track) {
                                return Html::a(Html::encode($track->name), ['item/view', 'id' => $track->id], [
                                    'class' => 'text-bold'
                                ]);
                            }
                        ],
                        [
                            'label' => 'Cover',
                            'format' => ['image', ['style' => 'max-width: 200px; height: auto; max-height: 200px;', 'class' => 'img-thumbnail']],
                            'value' => function ($model) {
                                return $model->getImageLink();
                            },
                        ],
                        [
                            'label' => 'Genres',
                            'value' => function($track) {
                                return implode(', ', \yii\helpers\ArrayHelper::getColumn($track->genres, 'name'));
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'controller' => 'item',
                            'template' => '{view} {update}',
                            'buttonOptions' => ['class' => 'btn btn-xs btn-default'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>

    </div>
</section>