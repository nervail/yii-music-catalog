<?php

namespace common\queue\jobs;

use yii\base\BaseObject;
use yii\queue\JobInterface;

class AlbumReleaseNotificationJob extends BaseObject implements JobInterface
{
    public string $userEmail;
    public string $username;
    public array $albumData;

    public function execute($queue)
    {
        \Yii::$app->mailer->compose(
            ['html' => 'newRelease-html', 'text' => 'newRelease-text'],
            [
                'username'  => $this->username,
                'album'     => $this->albumData,
            ]
        )
        ->setTo($this->userEmail)
        ->setSubject("Новый релиз от " . $this->albumData['artistName'] . "!")
        ->send();
    }
}
