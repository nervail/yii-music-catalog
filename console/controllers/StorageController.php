<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class StorageController extends Controller
{

public function actionUploadFixtures(): int
{
    $imagesPath = Yii::getAlias('@common/fixtures/images');

    $this->stdout("Images path: {$imagesPath}\n");

    if (!is_dir($imagesPath)) {
        $this->stderr("Directory not found!\n");
        return 1;
    }

    $this->stdout("Directory exists.\n");

    $storage = Yii::$app->storage;

    $this->stdout("Clearing bucket...\n");
    $storage->clearBucket();

    $this->stdout("Uploading fixture images...\n");

    $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator(
            $imagesPath,
            \FilesystemIterator::SKIP_DOTS
        )
    );

    $count = 0;

    foreach ($iterator as $file) {
        if (!$file->isFile()) {
            continue;
        }

        $count++;

        $filePath = $file->getPathname();

        $relativePath = str_replace(
            $imagesPath . DIRECTORY_SEPARATOR,
            '',
            $filePath
        );

        $key = str_replace(
            DIRECTORY_SEPARATOR,
            '/',
            $relativePath
        );

        $this->stdout("Uploading: {$key}\n");
        $this->stdout("Path: {$filePath}\n");

        $storage->uploadFixture($filePath, $key);

        $this->stdout("Uploaded!\n");
    }

    $this->stdout("Found files: {$count}\n");
    $this->stdout("Done.\n");

    return 0;
}
}