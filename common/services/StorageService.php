<?php

namespace common\services;

use Aws\S3\S3Client;
use Yii;

class StorageService
{
    /**
     * @var \Aws\S3\S3Client
     */
    private $s3;
    private $bucket = 'music-catalog';

    public function __construct()
    {
        $this->s3 = new S3Client(\Yii::$app->params['s3Params']);
    }

    public function uploadFile(string $tempPath, string $extension, string $contentType, string $folder): string
    {
        $randomName = \Yii::$app->security->generateRandomString(32);
        $key = $folder . '/' . $randomName . '.' . $extension;

        $this->s3->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
            'SourceFile' => $tempPath,
            'ContentType' => $contentType,
        ]);

        return $key;
    }

    public function delete(string $key): void
    {
        $this->s3->deleteObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);
    }

    public function getUrl(string $key): string
    {
        if (!$key) {
            return '';
        }

        return $this->s3->getObjectUrl($this->bucket, $key);
    }
}