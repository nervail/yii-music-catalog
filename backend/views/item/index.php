<?php

use common\models\Artist;
use common\models\Genre;
use Yii;
use yii\grid\GridView;
use yii\helpers\Html;

?>

<h1>Tracks</h1>

<p><?= Html::a('Add track', ['create'], ['class' => 'btn btn-success']) ?></p>

<?= Html::beginForm(['index'], 'get', ['class' => 'form-inline mb-2']); ?>

<?= Html::activeTextInput($searchModel, 'id', ['class' => 'form-control mr-2', 'placeholder' => 'ID']) ?>
<?= Html::activeTextInput($searchModel, 'name', ['class' => 'form-control mr-2', 'placeholder' => 'Name']) ?>
<?= Html::activeTextInput($searchModel, 'description', ['class' => 'form-control mr-2', 'placeholder' => 'Description']) ?>
<?= Html::activeTextInput($searchModel, 'status', ['class' => 'form-control mr-2', 'placeholder' => 'Status']) ?>
<?= Html::activeDropDownList($searchModel, 'artist_id', Artist::getList(), ['class' => 'form-control mr-2', 'prompt' => 'Choose an artist']) ?>
<?= Html::activeDropDownList($searchModel, 'genre_id', Genre::getList(), ['class' => 'form-control mr-2', 'prompt' => 'Choose a genre']) ?>
<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
<?= Html::a('Reset', ['index'], ['class' => 'btn btn-default ml-2']) ?>

<?= Html::endForm(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',
        'description',
        'image_url',
        [
            'label' => 'Artist',
            'value' => 'artist.name'
        ],  
        [
            'label' => 'Genre',
            'value' => 'genre.name'
        ],  
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
