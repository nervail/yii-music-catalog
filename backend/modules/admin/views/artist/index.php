<?php
use yii\grid\GridView;
use yii\helpers\Html;

?>

<h1>Artists</h1>

<p><?= Html::a('Add Artist', ['create'], ['class' => 'btn btn-success']) ?></p>

<?= Html::beginForm(['index'], 'get', ['class' => 'form-inline mb-2']); ?>

<?= Html::activeTextInput($searchModel, 'id', ['class' => 'form-control mr-2', 'placeholder' => 'ID']) ?>
<?= Html::activeTextInput($searchModel, 'name', ['class' => 'form-control mr-2', 'placeholder' => 'Name']) ?>
<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
<?= Html::a('Reset', ['index'], ['class' => 'btn btn-default ml-2']) ?>

<?= Html::endForm(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]) ?>