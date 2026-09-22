<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class ProjectController extends Controller
{
    public function actionReset(): int
    {
        if (!$this->confirm(
            'This will completely reset the database and MinIO bucket. Continue?'
        )) {
            $this->stdout("Cancelled.\n");
            return 0;
        }
        
        $this->stdout("=== Resetting project ===\n\n");

        $this->runStep(
            'Reset database',
            'migrate/fresh'
        );

        $this->runStep(
            'Load fixtures',
            'fixture/load',
            ['*']
        );

        $this->runStep(
            'Upload fixture images',
            'storage/upload-fixtures'
        );

        $this->runStep(
            'Initialize RBAC',
            'rbac/init'
        );

        $this->stdout("\n=== Project reset complete ===\n");

        return 0;
    }

    private function runStep(string $name, string $route, array $params = []): void
    {
        $this->stdout("--- {$name} ---\n");

        $result = Yii::$app->runAction($route, $params);

        if ($result !== 0) {
            $this->stderr("\nStep failed: {$name}\n");
            exit(1);
        }

        $this->stdout("\n");
    }
}