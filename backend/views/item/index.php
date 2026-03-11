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
