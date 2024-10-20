<?php

use yii\db\Migration;

class m170113_143527_set_all_active_in_products_table extends Migration
{
    public function up()
    {
        $this->execute('UPDATE `products` SET `active` = 1');
    }

    public function down()
    {
        echo "m170113_143527_set_all_active_in_products_table cannot be reverted.\n";

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
