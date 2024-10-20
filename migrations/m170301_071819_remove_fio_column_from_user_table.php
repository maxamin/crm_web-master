<?php

use yii\db\Migration;

class m170301_071819_remove_fio_column_from_user_table extends Migration
{
    public function up()
    {
        $this->dropColumn('user', 'fio');
    }

    public function down()
    {
        echo "m170301_071819_remove_fio_column_from_user_table cannot be reverted.\n";

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
