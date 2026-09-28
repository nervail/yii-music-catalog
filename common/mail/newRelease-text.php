<?php

/** @var yii\web\View $this */
/** @var string $username */
/** @var array $album */

?>

Привет, <?= $username ?>!

У твоего любимого исполнителя <?= $album['artistName'] ?> вышел новый альбом «<?= $album['name'] ?>»!

Слушать новый релиз:
<?= $album['link'] ?>