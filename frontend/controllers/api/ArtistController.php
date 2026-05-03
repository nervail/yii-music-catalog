<?php

namespace frontend\controllers\api;

use common\models\Artist;
use frontend\controllers\api\BaseApiController;

class ArtistController extends BaseApiController
{
    public $modelClass = Artist::class;
    public $resourceName = 'Artist';
}