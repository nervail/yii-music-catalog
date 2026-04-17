<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%item_genres}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%items}}`
 * - `{{%genres}}`
 */
class m260417_103250_create_junction_table_for_items_and_genres_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%item_genres}}', [
            'item_id' => $this->integer()->notNull(),
            'genre_id' => $this->integer()->notNull(),
            'PRIMARY KEY(item_id, genre_id)',
        ]);

        $this->addForeignKey('fk-item_genres-item', '{{%item_genres}}', 'item_id', '{{%items}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-item_genres-genre', '{{%item_genres}}', 'genre_id', '{{%genres}}', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-item_genres-genre', '{{%item_genres}}');
        $this->dropForeignKey('fk-item_genres-item', '{{%item_genres}}');
        $this->dropTable('{{%item_genres}}');
    }
}
