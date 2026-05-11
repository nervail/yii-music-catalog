<?php

use common\entities\Artist;
use common\entities\Genre;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var common\search\ItemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
?>

<h1>Tracks</h1>

<p><?= Html::a('Add track', ['create'], ['class' => 'btn btn-success']) ?></p>

<div class="item-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline mb-3'],
        'fieldConfig' => [
            'template' => "{input}\n{error}",
            'options' => ['class' => 'mr-2'],
        ],
    ]); ?>

    <?= $form->field($searchModel, 'id')->textInput(['placeholder' => 'ID', 'style' => 'width: 70px']) ?>
    
    <?= $form->field($searchModel, 'name')->textInput(['placeholder' => 'Name']) ?>
    
    <?= $form->field($searchModel, 'status')->textInput(['placeholder' => 'Status', 'style' => 'width: 100px']) ?>

    <?= $form->field($searchModel, 'artist_id')->dropDownList(Artist::getList(), [
        'prompt' => 'Choose an artist',
        'style' => 'width: 200px'
    ]) ?>

    <?= $form->field($searchModel, 'genre_ids')->widget(Select2::class, [
        'data' => Genre::getList(),
        'options' => [
            'placeholder' => 'Genres...',
            'multiple' => true, 
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <div class="form-group mr-2">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', ['index'], ['class' => 'btn btn-outline-secondary ml-1']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

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
            'label' => 'Genres',
            'value' => function ($model) {
                $genres = ArrayHelper::getColumn($model->genres, 'name');
                return implode(', ', $genres);
            }
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
