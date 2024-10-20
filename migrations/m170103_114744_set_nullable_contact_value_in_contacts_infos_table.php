<?php

use yii\db\Migration;

class m170103_114744_set_nullable_contact_value_in_contacts_infos_table extends Migration
{
    public function up()
    {
        $this->alterColumn('contacts_infos', 'contact_value', $this->string());
    }

    public function down()
    {
        echo "m170103_114744_set_nullable_contact_value_in_contacts_infos_table cannot be reverted.\n";

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
