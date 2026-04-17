<?php

use yii\db\Migration;

class m260417_105245_change_columns_at_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%items}}', 'album_id', $this->integer()->after('artist_id'));

        $this->dropForeignKey('fk-item-genre', 'items');
        $this->dropColumn('{{%items}}', 'genre_id');

        $this->addForeignKey('fk-items-album', '{{%items}}', 'album_id', '{{%albums}}', 'id', 'SET NULL ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-items-album', '{{%items}}');
        $this->addColumn('{{%items}}', 'genre_id', $this->integer()->notNull());
        $this->dropColumn('{{%items}}', 'album_id');
        $this->addForeignKey('fk-item-genre', '{{%items}}', 'genre_id', '{{%genres}}', 'id', 'CASCADE');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260417_105245_change_columns_at_items_table cannot be reverted.\n";

        return false;
    }
    */
}
