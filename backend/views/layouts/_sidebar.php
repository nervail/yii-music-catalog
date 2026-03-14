<?php 
use Yii;
use yii\helpers\Url;
?>


<?php
$role = Yii::$app->user->identity->role;

$badgeClass = match ($role) {
    'admin' => 'badge badge-danger',
    'moderator' => 'badge badge-purple',
    default => 'badge badge-secondary',
};
?>

<div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3">
    
        <div class="d-flex align-items-center">
            <div class="image">
                <img src="/dist/img/user2-160x160.jpg"
                    class="img-circle elevation-2"
                    alt="User Image">
            </div>

            <div class="info ml-2">
                <a class="d-block">
                    <?= Yii::$app->user->identity->username ?>
                </a>

                <span class="<?= $badgeClass ?>">
                    <?= strtoupper($role) ?>
                </span>
            </div>
        </div>

        <?php if (!Yii::$app->user->isGuest): ?>

            <?= \yii\helpers\Html::beginForm(['/site/logout'], 'post', [
                'class' => 'mt-2'
            ]) ?>

            <button class="btn btn-danger btn-sm btn-block">
                <i class="fas fa-sign-out-alt mr-1"></i>
                Logout
            </button>

            <?= \yii\helpers\Html::endForm() ?>

        <?php endif; ?>

      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?= Url::to('/') ?>" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Catalog
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>
                    Artists
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= Url::to('/artist') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Index</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= Url::to('/artist/create') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>
                    Genres
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= Url::to('/genre') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Index</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= Url::to('/genre/create') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>
                    Tracks
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= Url::to('/item') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Index</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= Url::to('/item/create') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Simple Link
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>