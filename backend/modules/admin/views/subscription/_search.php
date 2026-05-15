<?php

use common\entities\Artist;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\modules\admin\search\SubscriptionSearch $searchModel */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="card card-default">
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => ['class' => 'form-inline'],
        ]); ?>

        <?= $form->field($searchModel, 'id')->textInput(['placeholder' => 'ID', 'class' => 'form-control mr-2', 'style' => 'width: 80px'])->label(false) ?>
        
        <?= $form->field($searchModel, 'user_id')->textInput(['placeholder' => 'User ID', 'class' => 'form-control mr-2', 'style' => 'width: 120px'])->label(false) ?>
        
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