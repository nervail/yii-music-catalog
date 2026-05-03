<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Item $model */
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
                <h3 class="card-title">Track Details</h3>

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
                        'description',
                        [
                            'attribute' => 'image',
                            'format' => ['image', ['style' => 'max-width: 300px; height: auto;', 'class' => 'img-thumbnail']],
                            'value' => function ($model) {
                                return $model->getImageLink();
                            },
                        ],
                        [
                            'label' => 'Artist name',
                            'value' => $model->artist->name,
                        ],
                        'artist_id',
                        [
                            'label' => 'Genres',
                            'value' => implode(', ', ArrayHelper::getColumn($model->genres, 'name')),
                        ],
                        'status',
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

    </div>
</section>