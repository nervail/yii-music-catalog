<?php

use common\models\Artist;
use common\models\Genre;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Add Track';

$artists = Artist::find()->all();
$artistList = ArrayHelper::map($artists, 'id', 'name');

$genres = Genre::find()->all();
$genreList = ArrayHelper::map($genres, 'id', 'name');
?>
<div class="row justify-content-center pt-5">
    <div class="col-lg-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'item-form'
            ]); ?>

            <div class="card-body">
                <div class="form-group">
                    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Track name']) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'description')->textarea(['placeholder' => 'Track description']) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'image_url')->textInput(['placeholder' => 'Image_url']) // Пока что картинки текстом в бд добавляются ?> 
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'artist_id')->dropDownList(
                        $artistList
                    )->label('Artist')?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'genre_id')->dropDownList(
                        $genreList
                    )->label('Genre')?>
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
