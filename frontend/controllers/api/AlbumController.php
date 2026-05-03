<?php

namespace frontend\controllers\api;

use common\models\Album;
use frontend\controllers\api\BaseApiController;

class AlbumController extends BaseApiController
{
    public $modelClass = Album::class;
    public $resourceName = 'Album';
}