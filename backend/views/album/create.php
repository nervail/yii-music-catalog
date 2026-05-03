<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Album $model */

?>
<div class="album-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
