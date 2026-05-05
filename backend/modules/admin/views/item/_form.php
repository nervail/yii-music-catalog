<?php

use common\models\Album;
use common\models\Artist;
use common\models\Genre;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/** @var common\models\ItemForm $model */

$this->title = $model->scenario === 'create' ? 'Add Track' : 'Update Track' . ': ' . $model->name;

$this->registerCss("
    /* Контейнер для текста и крестика */
    .select2-container--krajee-bs5 .select2-selection--single .select2-selection__rendered {
        padding-right: 35px !important; /* Освобождаем место под иконки */
    }

    /* Сам крестик */
    .select2-container--krajee-bs5 .select2-selection--single .select2-selection__clear {
        position: absolute !important;
        right: 25px !important; /* Отодвигаем от правого края, где стрелка */
        top: 50% !important;
        transform: translateY(-50%) !important;
        z-index: 10 !important;
        cursor: pointer !important;
        padding: 0 5px !important;
    }

    /* Стрелочка (чтобы точно не мешала) */
    .select2-container--krajee-bs5 .select2-selection--single .select2-selection__arrow {
        right: 5px !important;
    }
");
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
                    <?php if ($model->currentImage): ?>
                        <div class="mb-2">
                            <p><strong>Current Image:</strong></p>
                            <?= Html::img($model->currentImage, [
                                'class' => 'img-thumbnail', 
                                'style' => 'width: 200px;'
                            ]) ?>
                        </div>
                    <?php endif; ?>

                    <?= $form->field($model, 'image')->fileInput()?> 
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'artist_id')->widget(Select2::class, [
                        'data' => Artist::getList(),
                        'options' => [
                            'id' => 'artist-id',
                            'placeholder' => 'First select an artist...',
                        ],
                    ])->label('Artist');?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'album_id')->widget(Select2::class, [
                        'data' => $model->getInitialAlbums(),
                        'options' => [
                            'id' => 'album-id',
                            'placeholder' => 'Select an album...',
                            'disabled' => empty($model->artist_id),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 0,
                            'ajax' => [
                                'url' => Url::to(['album/get-albums']),
                                'dataType' => 'json',
                                'delay' => 250,
                                'data' => new JsExpression("function(params) {
                                    return {
                                        artist_id: $('#artist-id').val(),
                                        q: params.term,
                                        page: params.page || 1
                                    };
                                }"),
                                'processResults' => new JsExpression("function(data) {
                                    return {
                                        results: data.results,
                                        pagination: data.pagination
                                    };
                                }"),
                            ],
                        ],
                    ])->label('Album')?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'genre_ids')->widget(Select2::class, [
                        'data' => Genre::getList(),
                        'options' => [
                            'placeholder' => 'Select genres...',
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label('Genres') ?>
                </div>
                
            </div>
    
            <div class="card-footer">
                <?= Html::submitButton($model->scenario === 'create' ? 'Create' : 'Update', [
                    'class' => 'btn btn-primary'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
<?php

$js = <<<JS
$('#artist-id').on('change', function() {
    var albumSelect = $('#album-id');
    albumSelect.val(null).trigger('change');
    
    if ($(this).val()) {
        albumSelect.prop('disabled', false);
    } else {
        albumSelect.prop('disabled', true);
    }
});
JS;

$this->registerJs($js);
?>