<?php

use yii\db\Migration;

class m161227_135633_add_order_column_to_contacts_info_types_table extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE `contacts_info_types` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->execute('UPDATE `contacts_info_types` SET `created_at` = NULL WHERE `created_at` = 0');
        $this->execute('UPDATE `contacts_info_types` SET `updated_at` = `created_at` WHERE `updated_at` = 0');
        $this->addColumn('contacts_info_types', 'ord', $this->integer(10)->unsigned()->notNull());
    }

    public function down()
    {
        $this->dropColumn('contacts_info_types', 'order');
    }
}
