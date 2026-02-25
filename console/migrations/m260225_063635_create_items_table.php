<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%items}}`.
 */
class m260225_063635_create_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%items}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(300)->notNull(),
            'image_url' => $this->string()->notNull(),
            'artist_id' => $this->integer()->notNull(),
            'genre_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-item-artist',
            'items',
            'artist_id',
            'artists',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-item-genre',
            'items',
            'genre_id',
            'genres',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-item-artist', 'items');
        $this->dropForeignKey('fk-item-genre', 'items');
        $this->dropTable('{{%items}}');
    }
}
