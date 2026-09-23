<?php

namespace common\services;

use common\entities\Album;

class AlbumService
{
    public function __construct(
        private StorageService $storage,
    ) {
    }

    public function delete(Album $album): bool
    {
        foreach ($album->items as $item) {
            $this->storage->delete($item->image_url);
        }

        $this->storage->delete($album->image_url);

        return $album->delete() !== false;
    }
}