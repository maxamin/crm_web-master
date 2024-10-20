<?php

use yii\db\Migration;

class m170116_115739_set_mysql_timestamps_on_leads_table extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE `leads` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }

    public function down()
    {
        echo "m170116_115739_set_mysql_timestamps_on_leads_table cannot be reverted.\n";

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
