<?php

use yii\db\Migration;

class m170113_141831_set_created_at_updated_at_to_null_where_000_in_products_table extends Migration
{
    public function up()
    {
        $this->execute('UPDATE `products` SET `created_at` = NULL WHERE `created_at` = 0');
        $this->execute('UPDATE `products` SET `updated_at` = NULL WHERE `updated_at` = 0');
    }

    public function down()
    {
        echo "m170113_141831_set_created_at_updated_at_to_null_where_000_in_products_table cannot be reverted.\n";

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
