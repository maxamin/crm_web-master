<?php

use yii\db\Migration;

class m170301_145106_update_products_table extends Migration
{
    public function up()
    {
        $this->execute('DELETE lp FROM leads_products lp JOIN products p WHERE p.id = lp.product_id AND p.name IN ("Тариф Международный", "ФАКТОРИНГ-дебитор", "ФАКТОРИНГ-клиент")');
        $this->execute('DELETE p FROM products p WHERE p.name IN ("Тариф Международный", "ФАКТОРИНГ-дебитор", "ФАКТОРИНГ-клиент")');
    }

    public function down()
    {
        echo "m170301_145106_update_products_table cannot be reverted.\n";

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
