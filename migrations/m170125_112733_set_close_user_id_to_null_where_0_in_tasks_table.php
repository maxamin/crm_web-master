<?php

use yii\db\Migration;

class m170125_112733_set_close_user_id_to_null_where_0_in_tasks_table extends Migration
{
    public function up()
    {
        $this->execute('UPDATE `tasks` SET `close_user_id` = NULL WHERE `close_user_id` = 0');
    }

    public function down()
    {
        echo "m170125_112733_set_close_user_id_to_null_where_0_in_tasks_table cannot be reverted.\n";

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
