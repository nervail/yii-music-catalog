<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Add genre';
?>

<div class="row justify-content-center pt-5">
    <div class="col-lg-6">

        <?php $form = ActiveForm::begin([
            'id' => 'artist-form',
        ]); ?>

        <div class="card card-primary">

            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>

            <div class="card-body">

                <?= $form->field($model, 'name')
                    ->textInput(['placeholder' => 'Genre name']) ?>

            </div>

            <div class="card-footer">
                <?= Html::submitButton('Submit', [
                    'class' => 'btn btn-primary'
                ]) ?>
            </div>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>