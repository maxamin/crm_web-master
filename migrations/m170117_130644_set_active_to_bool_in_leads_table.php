<?php

use yii\db\Migration;

class m170117_130644_set_active_to_bool_in_leads_table extends Migration
{
    public function up()
    {
        $this->alterColumn('leads', 'active', $this->boolean()->notNull());
    }

    public function down()
    {
        echo "m170117_130155_set_active_to_bool_in_leads_table cannot be reverted.\n";

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
