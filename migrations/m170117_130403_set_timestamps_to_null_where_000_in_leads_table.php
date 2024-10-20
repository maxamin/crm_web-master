<?php

use yii\db\Migration;

class m170117_130403_set_timestamps_to_null_where_000_in_leads_table extends Migration
{
    public function up()
    {
        $this->execute('UPDATE `leads` SET `created_at` = NULL WHERE `created_at` = 0');
        $this->execute('UPDATE `leads` SET `updated_at` = NULL WHERE `updated_at` = 0');
    }

    public function down()
    {
        echo "m170117_130403_set_timestamps_to_null_where_000_in_leads_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
