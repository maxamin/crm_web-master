<?php

use yii\db\Migration;

/**
 * Handles adding verified_at to table `user`.
 */
class m170301_095356_add_verified_at_column_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'verified_at', $this->integer()->null());
    }

    public function down()
    {
        $this->dropcolumn('{{%user}}', 'verified_at');
    }
}
