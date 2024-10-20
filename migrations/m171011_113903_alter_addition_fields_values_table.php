<?php

use yii\db\Migration;

class m171011_113903_alter_addition_fields_values_table extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            '{{%fk_addition_fields_values_contacts}}',
            '{{%addition_fields_values}}',
            'foregin_table_id',
            '{{%contacts}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        echo "m171011_113903_alter_addition_fields_values_table cannot be reverted.\n";

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
