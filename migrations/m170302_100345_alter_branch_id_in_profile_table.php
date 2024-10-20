<?php

use yii\db\Migration;

class m170302_100345_alter_branch_id_in_profile_table extends Migration
{
    public function up()
    {
        $this->execute('UPDATE profile p SET p.branch_id = (SELECT b.id FROM branch b WHERE b.code LIKE "ГО")');
        $this->alterColumn('{{%profile}}', 'branch_id', $this->integer(10)->unsigned()->notNull());
    }

    public function down()
    {
        echo "m170302_100345_alter_branch_id_in_profile_table cannot be reverted.\n";

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
