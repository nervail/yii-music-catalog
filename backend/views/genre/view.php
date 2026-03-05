<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

?>

<section class="content-header">
    <div class="container-fluid">
        <h1><?= Html::encode($model->name) ?></h1>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Genre Details</h3>

                <div class="card-tools">
                    <?= Html::a('Update', ['update', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-primary'
                    ]) ?>

                    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'name',
                    ],
                ]) ?>

            </div>

        </div>

    </div>
</section>