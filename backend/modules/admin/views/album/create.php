<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\entities\Album $model */

?>
<div class="album-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
