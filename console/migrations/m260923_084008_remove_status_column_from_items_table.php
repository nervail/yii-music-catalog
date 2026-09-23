<?php

use yii\db\Migration;

class m260923_084008_remove_status_column_from_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%items}}', 'status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->addColumn(
            '{{%items}}', 
            'status', 
            $this->integer()->notNull()->defaultValue(1)->after('album_id')
        );
    }
}
