<?php

use common\models\Artist;
use common\models\Genre;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Add Track';
?>
<div class="row justify-content-center pt-5">
    <div class="col-lg-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'item-form',
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
            ]); ?>

            <div class="card-body">
                <div class="form-group">
                    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Track name']) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'description')->textarea(['placeholder' => 'Track description']) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'image')->fileInput(['placeholder' => 'Image',]) ?> 
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'artist_id')->dropDownList(Artist::getList())->label('Artist')?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'genre_id')->dropDownList(Genre::getList())->label('Genre')?>
                </div>
                
            </div>
    
            <div class="card-footer">
                <?= Html::submitButton('Submit', [
                    'class' => 'btn btn-primary'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
