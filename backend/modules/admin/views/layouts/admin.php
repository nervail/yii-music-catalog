<?php

use backend\assets\AdminLteAsset;
use backend\assets\AppAsset;
use backend\widgets\ToastAlert;
use yii\bootstrap5\Html;

AdminLteAsset::register($this);
AppAsset::register($this);

$this->beginPage();
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->registerCsrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>
</head>

<body class="hold-transition sidebar-mini">

<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link"
                   data-widget="pushmenu"
                   href="#"
                   role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">

            <li class="nav-item">
                <a class="nav-link"
                   data-widget="fullscreen"
                   href="#"
                   role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                   data-widget="control-sidebar"
                   data-slide="true"
                   href="#"
                   role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>

        </ul>

    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="/" class="brand-link">
            <span class="brand-text font-weight-light">
                Music Catalog
            </span>
        </a>

        <?= $this->render('_sidebar') ?>

    </aside>
    <!-- /.sidebar -->

    <!-- Content Wrapper -->
    <div class="content-wrapper">

        <div class="content pt-4 pl-4">

            <?= ToastAlert::widget() ?>

            <?= $content ?>

        </div>

    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">

        <strong>
            Music Catalog
        </strong>

        <div class="float-right d-none d-sm-inline">
            Admin panel
        </div>

    </footer>
    <!-- /.main-footer -->

</div>
<!-- ./wrapper -->

<?php $this->endBody() ?>

</body>
</html>

<?php $this->endPage() ?>