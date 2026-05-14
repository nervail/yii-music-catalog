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
            'user_id' => $this->integer()->notNull(),
            'artist_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-subscription-user',
            '{{%subscriptions}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-subscription-artist',
            '{{%subscriptions}}',
            'artist_id',
            '{{%artists}}',
            'id',
            'CASCADE'
        );

        // Уникальный индекс, чтобы нельзя было подписаться дважды
        $this->createIndex(
            'idx-subscription-user-artist',
            '{{%subscriptions}}',
            ['user_id', 'artist_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-subscription-user', '{{%subscriptions}}');
        $this->dropForeignKey('fk-subscription-artist', '{{%subscriptions}}');
        $this->dropIndex('idx-subscription-user-artist', '{{%subscriptions}}');
        $this->dropTable('{{%subscriptions}}');
    }
}
