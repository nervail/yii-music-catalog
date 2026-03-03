<?php
use yii\grid\GridView;
use yii\helpers\Html;

?>
<h1>Artists</h1>

<p><?= Html::a('Add Artist', ['create'], ['class' => 'btn btn-success']) ?></p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]) ?>