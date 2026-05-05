<?php

use common\models\Artist;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\AlbumForm $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = $model->scenario === $model::SCENARIO_CREATE ? 'Create Album' : 'Update Album: ' . $model->name;

?>
<div class="row justify-content-center pt-5">
    <div class="col-lg-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'album-form',
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
            ]); ?>

            <div class="card-body">
                <div class="form-group">
                    <?= $form->field($model, 'name')->textInput([
                        'placeholder' => 'Album name',
                        'maxlength' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'artist_id')->dropDownList(
                        Artist::getList(), 
                        ['prompt' => 'Select Artist...']
                    )->label('Artist') ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'release_date')->textInput([
                        'type' => 'date',
                        'placeholder' => 'YYYY-MM-DD'
                    ]) ?>
                </div>

                <?php if ($model->currentImage): ?>
                    <div class="form-group">
                        <div class="mb-2">
                            <p><strong>Current Cover:</strong></p>
                            <?= Html::img($model->currentImage, [
                                'class' => 'img-thumbnail shadow-sm', 
                                'style' => 'width: 200px;'
                            ]) ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <?= $form->field($model, 'image')->fileInput(['placeholder' => 'Image']) ?> 
                </div>
            </div>
    
            <div class="card-footer text-right">
                <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default mr-2']) ?>
                <?= Html::submitButton($model->scenario === $model::SCENARIO_CREATE ? 'Create' : 'Save Changes', [
                    'class' => 'btn btn-primary'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>