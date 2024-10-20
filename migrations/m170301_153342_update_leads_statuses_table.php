<?php

use yii\db\Migration;

class m170301_153342_update_leads_statuses_table extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE `lead_statuses` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->execute('UPDATE `lead_statuses` SET `created_at` = NOW() WHERE `created_at` = 0');

        $this->execute('UPDATE lead_statuses ls SET ls.name = "Первый контакт" WHERE ls.name LIKE "Квалификация"');
        $this->execute('UPDATE lead_statuses ls SET ls.name = "Презентация продукта" WHERE ls.name LIKE "Презентация"');
        $this->execute('UPDATE lead_statuses ls SET ls.name = "Реализация сделки" WHERE ls.name LIKE "Коммерческое предложение"');
        $this->execute('UPDATE lead_statuses ls SET ls.name = "Сделка завершена уcпешно" WHERE ls.name LIKE "Сделка выиграна"');
        $this->execute('UPDATE lead_statuses ls SET ls.name = "Сделка не реализована" WHERE ls.name LIKE "Сделка проиграна"');
    }

    public function down()
    {
        echo "m170301_153342_update_leads_statuses_table cannot be reverted.\n";

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
