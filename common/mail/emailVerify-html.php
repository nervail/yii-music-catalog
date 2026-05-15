<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\entities\User $user */
/** @var string $verifyLink */

?>
<div class="verify-email">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to verify your email:</p>

    <p><?= Html::a('Verify my email', $verifyLink, ['style' => 'background-one: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;']) ?></p>

    <p>Or copy this link to your browser:</p>
    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
