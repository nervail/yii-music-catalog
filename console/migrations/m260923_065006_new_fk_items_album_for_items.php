<?php

use yii\db\Migration;

class m260923_065006_new_fk_items_album_for_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-items-album', '{{%items}}');
        
        $this->addForeignKey(
            'fk-items-album',
            '{{%items}}',
            'album_id',
            '{{%albums}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-items-album', '{{%items}}');

        $this->addForeignKey(
            'fk-items-album',
            '{{%items}}',
            'album_id',
            '{{%albums}}',
            'id',
            'RESTRICT'
        );
    }
}
