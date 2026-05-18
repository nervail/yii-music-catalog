<?php

/** @var yii\web\View $this */
/** @var string $username */
/** @var array $album */ // Наш массив данных без запросов к БД

?>
Привет, <?= $username ?>!

У твоего любимого исполнителя <?= $album['artistName'] ?> вышел новый альбом «<?= $album['name'] ?>»!

Слушай новый релиз прямо сейчас на нашей платформе!