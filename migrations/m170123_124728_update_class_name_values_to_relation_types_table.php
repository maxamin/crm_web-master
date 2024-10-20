<?php

use yii\db\Migration;

class m170123_124728_update_class_name_values_to_relation_types_table extends Migration
{
    public function up()
    {
        $this->execute("UPDATE `relation_types` SET `class_name` = '" . addslashes(\app\models\Leads::className()) . "' WHERE `table` = '" . \app\models\Leads::tableName(). "'");
        $this->execute("UPDATE `relation_types` SET `class_name` = '" . addslashes(\app\models\Contacts::className()) . "' WHERE `table` = '" . \app\models\Contacts::tableName(). "'");
    }

    public function down()
    {
        echo "m170123_124726_insert_class_name_values_to_relation_types_table cannot be reverted.\n";

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
