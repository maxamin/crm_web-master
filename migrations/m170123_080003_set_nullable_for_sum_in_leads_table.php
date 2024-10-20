<?php

use yii\db\Migration;

class m170123_080003_set_nullable_for_sum_in_leads_table extends Migration
{
    public function up()
    {
        $this->alterColumn('leads', 'sum', $this->integer());
    }

    public function down()
    {
        echo "m170123_080003_set_nullable_for_sum_in_leads_table cannot be reverted.\n";

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
