<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\entities\User $user */
/** @var string $resetLink */

?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
