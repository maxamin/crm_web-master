<?php

use yii\db\Migration;

class m170123_123400_add_class_name_column_to_relation_types_table extends Migration
{
    public function up()
    {
        $this->addColumn('relation_types', 'class_name', $this->string()->notNull());
    }

    public function down()
    {
        $this->dropColumn('relation_types', 'class_name');
    }
}
