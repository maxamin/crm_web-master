<?php

use yii\db\Migration;

class m170227_103705_rename_users_table_to_user extends Migration
{
    public function up()
    {
        $this->renameTable('users', 'user');
    }

    public function down()
    {
        $this->renameTable('user', 'users');
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
