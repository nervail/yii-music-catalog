<?php

use yii\db\Migration;

class m260225_060014_rename_user_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('user', 'users');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('users', 'user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260225_060014_rename_user_to_users cannot be reverted.\n";

        return false;
    }
    */
}
