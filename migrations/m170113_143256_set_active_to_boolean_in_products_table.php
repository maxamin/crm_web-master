<?php

use yii\db\Migration;

class m170113_143256_set_active_to_boolean_in_products_table extends Migration
{
    public function up()
    {
        $this->alterColumn('products', 'active', $this->boolean()->notNull());
    }

    public function down()
    {
        echo "m170113_143256_set_active_to_boolean_in_products_table cannot be reverted.\n";

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
