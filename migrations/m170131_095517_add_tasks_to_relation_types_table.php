<?php

use yii\db\Migration;

class m170131_095517_add_tasks_to_relation_types_table extends Migration
{
    public function up()
    {
        $this->insert('relation_types', [
            'table' => 'tasks',
            'name' => 'Задача',
            'class_name' => \app\models\Tasks::className(),
            'alias' => 'tasks',
        ]);
    }

    public function down()
    {
        echo "m170131_073408_alter_columns_in_comments_table cannot be reverted.\n";

        return false;
    }
}
