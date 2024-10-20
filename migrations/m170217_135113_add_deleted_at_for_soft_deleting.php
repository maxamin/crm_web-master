<?php

use yii\db\Migration;

class m170217_135113_add_deleted_at_for_soft_deleting extends Migration
{
    public function up()
    {
        $this->addColumn('contacts', 'deleted_at', 'TIMESTAMP NULL');
        $this->addColumn('addition_fields_values', 'deleted_at', 'TIMESTAMP NULL');
        $this->addColumn('comments', 'deleted_at', 'TIMESTAMP NULL');
        $this->addColumn('contacts_infos', 'deleted_at', 'TIMESTAMP NULL');
        $this->addColumn('contacts_link', 'deleted_at', 'TIMESTAMP NULL');
        $this->addColumn('leads', 'deleted_at', 'TIMESTAMP NULL');
        $this->addColumn('leads_products', 'deleted_at', 'TIMESTAMP NULL');
        $this->addColumn('tasks', 'deleted_at', 'TIMESTAMP NULL');
    }

    public function down()
    {
        echo "m170217_135113_add_deleted_at_for_soft_deleting cannot be reverted.\n";

        return false;
    }
}
