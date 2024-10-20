<?php

use yii\db\Migration;

class m170123_145205_change_columns_in_tasks_table extends Migration
{
    public function up()
    {
        $this->execute('UPDATE `tasks` SET `day` = "2016-01-01" WHERE `day` = 0');
        $this->alterColumn('tasks', 'status', $this->boolean()->notNull());
        $this->alterColumn('tasks', 'close_user_id', $this->integer(10)->unsigned()->defaultValue(null));
    }

    public function down()
    {
        echo "m170123_145205_change_columns_in_tasks_table cannot be reverted.\n";

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
