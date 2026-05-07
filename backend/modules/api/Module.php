<?php

namespace backend\modules\api;


class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\api\controllers';
    public $defaultRoute = 'site/index';

    public function init()
    {
        parent::init();
    }
}