<?php

use yii\db\Migration;

class m170322_091629_alter_columns_in_contacts_table extends Migration
{
    public function up()
    {
        $this->execute('UPDATE contacts c SET c.okpo = NULL WHERE c.okpo = ""');
        $this->alterColumn('contacts', 'name', $this->string()->notNull());
        $this->alterColumn('contacts', 'okpo', $this->string(12)->unique());
        $this->alterColumn('contacts', 'active', $this->boolean()->notNull());
    }

    public function down()
    {
        echo "m170322_091629_alter_columns_in_contacts_table cannot be reverted.\n";

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
