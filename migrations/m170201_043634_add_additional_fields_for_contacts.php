<?php

use yii\db\Migration;

class m170201_043634_add_additional_fields_for_contacts extends Migration
{
    public function up()
    {
        $this->insert('addition_fields', [
            'active' => '1',
            'type' => '2',
            'name' => 'Контактное лицо',
            'is_directory' => '0',
        ]);
        $this->insert('addition_fields', [
            'active' => '1',
            'type' => '2',
            'name' => 'Телефон контактного лица',
            'is_directory' => '0',
        ]);
        $this->insert('addition_fields', [
            'active' => '1',
            'type' => '2',
            'name' => 'Должность контактного лица',
            'is_directory' => '0',
        ]);
    }

    public function down()
    {
        echo "m170201_043634_add_additional_fields_for_contacts cannot be reverted.\n";

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
