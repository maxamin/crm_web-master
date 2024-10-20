<?php

use yii\db\Migration;

class m170405_085350_replace_legal_natural_views extends Migration
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
        $this->execute('DROP VIEW `natural`');
        $this->execute('DROP VIEW `legal`');
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