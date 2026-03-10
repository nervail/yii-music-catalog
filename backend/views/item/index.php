<?php

use Yii;
use yii\grid\GridView;
?>

<h1>Tracks</h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',   
        'description',   
        'image_url',   
        'artist_id',   
        'genre_id',   
        'status',   
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y H:i');
            }
        ],
        [
            'attribute' => 'updated_at',
            'value' => function ($model) {
                return Yii::$app->formatter->asDate($model->updated_at, 'php:d.m.Y H:i');
            }
        ],
    ]
]) ?>
