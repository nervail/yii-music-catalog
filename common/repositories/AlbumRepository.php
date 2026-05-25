<?php

namespace common\repositories;

use common\entities\Album;
use common\exceptions\RepositoryException;

class AlbumRepository
{   
    public function publish(Album $album): void
    {
        $album->status = Album::STATUS_PUBLISHED;
        $album->published_at = time();

        $this->save($album, false, ['status', 'published_at']);
    }

    public function save(Album $album, bool $runValidation = true, ?array $attributeNames = null): void
    {
        if (!$album->save($runValidation, $attributeNames)) {
            throw new RepositoryException();
        }
    }
}