<?php

use yii\db\Migration;

class m170228_150554_copy_user_fio_to_profile_name extends Migration
{
    public function up()
    {
        $this->execute('UPDATE `profile` AS `p` INNER JOIN `user` AS `u` ON `p`.`user_id` = `u`.`id` SET `p`.`name` = `u`.`fio`');
    }

    public function down()
    {
        echo "m170228_150554_copy_user_fio_to_profile_name cannot be reverted.\n";

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
