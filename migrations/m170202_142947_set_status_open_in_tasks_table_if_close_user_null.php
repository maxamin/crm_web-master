<?php

use yii\db\Migration;

class m170202_142947_set_status_open_in_tasks_table_if_close_user_null extends Migration
{
    public function up()
    {
        $this->execute('UPDATE `tasks` SET `status` = FALSE WHERE `close_user_id` = NULL');
    }

    public function down()
    {
        echo "m170202_142940_set_status_open_in_tasks_table_if_close_user_null cannot be reverted.\n";

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
