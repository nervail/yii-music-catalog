<?php

use common\models\User;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Update User';

?>
<div class="row justify-content-center pt-5">
    <div class="col-lg-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'user-form'
            ]); ?>

            <div class="card-body">
                <div class="form-group">
                    <?= $form->field($model, 'username')->textInput(['placeholder' => 'Username']) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email']) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'status')->dropDownList(User::getStatusList()) ?> 
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'role')->dropDownList(User::getRoleList(), ['prompt' => 'Select Role...']) ?> 
                </div>

                
            </div>
    
            <div class="card-footer">
                <?= Html::submitButton('Update', [
                    'class' => 'btn btn-primary'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
