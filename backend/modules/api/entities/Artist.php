<?php

namespace backend\modules\api\entities;

use common\entities\Artist as BaseArtist;
use common\entities\Album;
use Override;

class Artist extends BaseArtist
{
    #[Override]
    public function getAlbums()
    {
        return parent::getAlbums()
            ->andWhere([
                Album::tableName() . '.status' => Album::STATUS_PUBLISHED
            ]);
    }
}