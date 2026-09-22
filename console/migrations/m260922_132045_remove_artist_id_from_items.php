<?php

use yii\db\Migration;

class m260922_132045_remove_artist_id_from_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-item-artist', '{{%items}}');
        $this->dropColumn('{{%items}}', 'artist_id');

        $this->dropForeignKey('fk-items-album', '{{%items}}');

        $this->alterColumn('{{%items}}', 'album_id', $this->integer()->notNull());

        $this->addForeignKey(
            'fk-items-album',
            '{{%items}}',
            'album_id',
            '{{%albums}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-items-album', '{{%items}}');

        $this->alterColumn('{{%items}}', 'album_id', $this->integer()->null());

        $this->addForeignKey('fk-items-album', '{{%items}}', 'album_id', '{{%albums}}', 'id', 'SET NULL');

        $this->addColumn('{{%items}}', 'artist_id', $this->integer()->null()->after('image_url'));

        $this->addForeignKey(
            'fk-item-artist',
            'items',
            'artist_id',
            'artists',
            'id',
            'CASCADE'
        );
    }
}
