<?php

/** @var yii\web\View $this */
/** @var common\entities\User $user */
/** @var string $verifyLink */
?>
Hello <?= $user->username ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
