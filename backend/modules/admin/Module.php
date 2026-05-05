<?php

namespace backend\modules\admin;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\admin\controllers';
    public $defaultRoute = 'site/index';

    public function init()
    {
        parent::init();
    }
}