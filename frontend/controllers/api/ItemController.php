<?php

namespace frontend\controllers\api;

use common\models\Item;
use frontend\controllers\api\BaseApiController;

class ItemController extends BaseApiController
{
    public $modelClass = Item::class;
    public $resourceName = 'Item';
}