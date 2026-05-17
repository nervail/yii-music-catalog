<?php

use yii\db\Migration;

class m260517_030350_delete_columns_release_date_and_create_columns_status_and_published_at_to_albums extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%albums}}', 'release_date');
        $this->addColumn('{{%albums}}', 'status', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%albums}}', 'published_at', $this->integer()->null()); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%albums}}', 'published_at');
        $this->dropColumn('{{%albums}}', 'status');
        $this->addColumn('{{%albums}}', 'release_date', $this->date()->null());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260517_030350_delete_columns_release_date_and_create_columns_status_and_published_at_to_albums cannot be reverted.\n";

        return false;
    }
    */
}
