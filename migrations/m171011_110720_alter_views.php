<?php

use yii\db\Migration;

class m171011_110720_alter_views extends Migration
{
    public function up()
    {
        $this->execute('DROP VIEW `natural`');
        $this->execute('DROP VIEW `legal`');
        $this->execute('CREATE VIEW `natural` AS SELECT `contacts`.* FROM `contacts` WHERE `contacts`.`type` = 1');
        $this->execute('CREATE VIEW `legal` AS SELECT `contacts`.* FROM `contacts` WHERE `contacts`.`type` = 2');
    }

    public function down()
    {
        echo "m171011_110720_alter_views cannot be reverted.\n";

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
