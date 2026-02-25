<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscriptions}}`.
 */
class m260225_063042_create_subscriptions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscriptions}}', [
            'id' => $this->primaryKey(),
            'following_user_id' => $this->integer()->notNull(),
            'followed_artist_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-sub-user',
            'subscriptions',
            'following_user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-sub-artist',
            'subscriptions',
            'followed_artist_id',
            'artists',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-subscription-user-artist',
            'subscriptions',
            ['following_user_id', 'followed_artist_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-sub-user', 'subscriptions');
        $this->dropForeignKey('fk-sub-artist', 'subscriptions');
        $this->dropIndex('idx-subscription-user-artist', 'subscriptions');
        $this->dropTable('{{%subscriptions}}');
    }
}
