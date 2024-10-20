<?php

use yii\db\Migration;

class m170123_140940_alter_class_name_column_in_relation_types_table extends Migration
{
    public function up()
    {
        $this->alterColumn('relation_types', 'class_name', $this->string()->notNull()->unique());
    }

    public function down()
    {
        echo "m170123_140940_alter_class_name_column_in_relation_types_table cannot be reverted.\n";

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
