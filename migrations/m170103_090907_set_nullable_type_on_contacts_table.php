<?php

use yii\db\Migration;

class m170103_090907_set_nullable_type_on_contacts_table extends Migration
{
    public function up()
    {
        $this->alterColumn('contacts_infos', 'type', $this->integer());
    }

    public function down()
    {
        echo "m170103_090907_set_nullable_type_on_contacts_table cannot be reverted.\n";

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
