<?php

use yii\db\Migration;

class m170302_070228_delete_status_in_leads_statuses_table extends Migration
{
    public function up()
    {
        $this->execute('UPDATE leads l SET l.lead_status_id = (SELECT ls.id FROM lead_statuses ls WHERE ls.name LIKE "Реализация сделки") WHERE l.lead_status_id = (SELECT ls.id FROM lead_statuses ls WHERE ls.name LIKE "Предварительное предложение")');
        $this->delete('{{%lead_statuses}}', ['name' => 'Предварительное предложение']);
    }

    public function down()
    {
        echo "m170302_070228_delete_status_in_leads_statuses_table cannot be reverted.\n";

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
