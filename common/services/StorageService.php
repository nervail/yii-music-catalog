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
        if (!$key) return;
        
        if (filter_var($key, FILTER_VALIDATE_URL)) {
            return;
        }
        
        try {
            $this->s3->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $key,
            ]);
        } catch (\Aws\S3\Exception\S3Exception $e) {
            \Yii::error("Ошибка удаления из S3: " . $e->getMessage());
        }
    }

    public function getUrl(string $key): string
    {
        if (!$key) {
            return '';
        }

        return rtrim(Yii::$app->params['storageHost'], '/') . '/'
        . $this->bucket . '/'
        . ltrim($key, '/');
    }

    public function clearBucket(): void
    {
        $objects = $this->s3->listObjectsV2([
            'Bucket' => $this->bucket,
        ]);

        if (empty($objects['Contents'])) {
            return;
        }

        $keys = [];

        foreach ($objects['Contents'] as $object) {
            $keys[] = ['Key' => $object['Key']];
        }

        $this->s3->deleteObjects([
            'Bucket' => $this->bucket,
            'Delete' => [
                'Objects' => $keys,
                'Quiet' => true,
            ],
        ]);
    }

    public function uploadFixture(string $filePath, string $key): void
    {
        $this->s3->putObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'SourceFile' => $filePath,
            'ContentType' => mime_content_type($filePath),
        ]);
    }
}