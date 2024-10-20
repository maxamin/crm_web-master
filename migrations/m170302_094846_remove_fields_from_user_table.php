<?php

use yii\db\Migration;

class m170302_094846_remove_fields_from_user_table extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%user}}', 'city_id');
        $this->dropColumn('{{%user}}', 'active');
    }

    public function down()
    {
        echo "m170302_094846_remove_fields_from_user_table cannot be reverted.\n";

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
