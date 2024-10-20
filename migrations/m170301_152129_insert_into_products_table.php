<?php

use yii\db\Migration;

class m170301_152129_insert_into_products_table extends Migration
{
    public function up()
    {
        $this->insert('{{%products}}', [
            'name' => 'БЛИЦ-ОВЕРДРАФТ',
            'active' => 1,
            'type' => 1,
        ]);
        $this->insert('{{%products}}', [
            'name' => 'ГАРАНТИЯ ИСПОЛНЕНИЯ',
            'active' => 1,
            'type' => 1,
        ]);
        $this->insert('{{%products}}', [
            'name' => 'POS-ТЕРМИНАЛ',
            'active' => 1,
            'type' => 1,
        ]);
    }

    public function down()
    {
        echo "m170301_152129_insert_into_products_table cannot be reverted.\n";

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
