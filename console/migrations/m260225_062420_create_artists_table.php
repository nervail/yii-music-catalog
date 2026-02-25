<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%artists}}`.
 */
class m260225_062420_create_artists_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%artists}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%artists}}');
    }
}
