<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%albums}}`.
 */
class m260417_104516_create_albums_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%albums}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'artist_id' => $this->integer()->notNull(),
            'release_date' => $this->date(),
            'image_url' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-album-artist', '{{%albums}}', 'artist_id', '{{%artists}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-album-artist', '{{%albums}}');
        $this->dropTable('{{%albums}}');
    }
}
