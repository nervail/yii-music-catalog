<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Add artist';
?>
<div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Please fill out the following field to add new artist</p>

        <?php $form = ActiveForm::begin(['id' => 'artist-form']); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Add', ['class' => 'btn btn-primary btn-block', 'name' => 'artist-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
