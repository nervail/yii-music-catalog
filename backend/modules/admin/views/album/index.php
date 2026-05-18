<?php

use common\entities\Artist;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\search\AlbumSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Albums Catalog';
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="col-sm-6 text-right">
                <?= Html::a('<i class="fas fa-plus"></i> Add Album', ['create'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card card-default">
            <div class="card-body">
                <?php $form = ActiveForm::begin([
                    'action' => ['index'],
                    'method' => 'get',
                    'options' => ['class' => 'form-inline'],
                ]); ?>

                <?= $form->field($searchModel, 'id')->textInput(['placeholder' => 'ID', 'class' => 'form-control mr-2', 'style' => 'width: 80px'])->label(false) ?>
                <?= $form->field($searchModel, 'name')->textInput(['placeholder' => 'Album Name', 'class' => 'form-control mr-2'])->label(false) ?>
                <?= $form->field($searchModel, 'artist_id')->dropDownList(Artist::getList(), [
                    'prompt' => 'All Artists',
                    'class' => 'form-control mr-2',
                    'style' => 'min-width: 200px'
                ])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-primary mr-1']) ?>
                    <?= Html::a('<i class="fas fa-sync"></i>', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

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
                            'label' => 'Cover',
                            'format' => ['image', ['style' => 'max-width: 100px; height: auto;', 'class' => 'img-thumbnail']],
                            'value' => function ($model) {
                                return $model->getImageLink();
                            }
                        ],
                        [
                            'attribute' => 'name',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::a(Html::encode($model->name), ['view', 'id' => $model->id], ['class' => 'text-dark font-weight-bold']);
                            }
                        ],
                        [
                            'label' => 'Artist',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a(Html::encode($model->artist->name), ['artist/view', 'id' => $model->artist_id], ['class' => 'badge badge-info p-2']);
                            }
                        ],
                        [
                            'label' => 'Status',
                            'format' => 'raw',
                            'value' => function($model) {

                                $classes = [
                                    $model::STATUS_DRAFT => 'badge badge-secondary',
                                    $model::STATUS_PUBLISHED => 'badge badge-success',
                                    $model::STATUS_ARCHIVED => 'badge badge-dark',
                                ];

                                $class = $classes[$model->status] ?? 'badge badge-light';

                                return Html::tag(
                                    'span',
                                    Html::encode($model->getStatusLabel()),
                                    ['class' => $class]
                                );
                            },
                        ],
                        [
                            'label' => 'Published at',
                            'value' => function($model) {
                                return $model->published_at
                                    ? \Yii::$app->formatter->asDatetime($model->published_at)
                                    : 'Not published';
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'headerOptions' => ['style' => 'width: 100px'],
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url) {
                                    return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-xs btn-default text-primary']);
                                },
                                'update' => function ($url) {
                                    return Html::a('<i class="fas fa-edit"></i>', $url, ['class' => 'btn btn-xs btn-default text-success']);
                                },
                                'delete' => function ($url) {
                                    return Html::a('<i class="fas fa-trash"></i>', $url, [
                                        'class' => 'btn btn-xs btn-default text-danger',
                                        'data-method' => 'post',
                                        'data-confirm' => 'Are you sure?',
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