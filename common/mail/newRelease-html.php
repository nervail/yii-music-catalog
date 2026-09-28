<?php

use \yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $username */
/** @var array $album */

?>

<h2>Привет, <?= Html::encode($username) ?>!</h2>

<p>
    У твоего любимого исполнителя
    <strong><?= Html::encode($album['artistName']) ?></strong>
    вышел новый альбом
    «<?= Html::encode($album['name']) ?>»!
</p>

<p>
    <a href="<?= Html::encode($album['link']) ?>">
        Слушать новый релиз
    </a>
</p>