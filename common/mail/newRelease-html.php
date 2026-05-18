<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $username */
/** @var array $album */
?>
<div class="new-release-email">
    <h2>Привет, <?= Html::encode($username) ?>!</h2>
    
    <p>У твоего любимого исполнителя <strong><?= Html::encode($album['artistName']) ?></strong> вышел новый альбом!</p>
    
    <div class="album-card" style="border: 1px solid #ccc; padding: 15px; border-radius: 8px; margin-top: 15px;">
        <h3>Альбом: «<?= Html::encode($album['name']) ?>»</h3>
        <p>Слушай прямо сейчас на нашей платформе.</p>
    </div>
</div>