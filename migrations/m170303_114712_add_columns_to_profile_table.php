<?php

use yii\db\Migration;

class m170303_114712_add_columns_to_profile_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%profile}}', 'internal_phone', $this->string(4));
        $this->addColumn('{{%profile}}', 'contact_phone', $this->string(13));
        $this->addColumn('{{%profile}}', 'avatar', $this->string(64));
    }

    public function down()
    {
        $this->dropColumn('{{%profile}}', 'internal_phone');
        $this->dropColumn('{{%profile}}', 'contact_phone');
        $this->dropColumn('{{%profile}}', 'avatar');
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
