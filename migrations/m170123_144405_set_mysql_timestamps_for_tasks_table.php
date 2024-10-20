<?php

use yii\db\Migration;

class m170123_144405_set_mysql_timestamps_for_tasks_table extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE `tasks` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->execute('UPDATE `tasks` SET `created_at` = NULL WHERE `created_at` = 0');
        $this->execute('UPDATE `tasks` SET `updated_at` = NULL WHERE `updated_at` = 0');
    }

    public function down()
    {
        echo "m170123_144405_set_mysql_timestamps_for_tasks_table cannot be reverted.\n";

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
