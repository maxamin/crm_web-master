<?php

use yii\db\Migration;

class m170131_073408_alter_columns_in_comments_table extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE `comments` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->execute('UPDATE `comments` SET `created_at` = NULL WHERE `created_at` = 0');
        $this->execute('UPDATE `comments` SET `updated_at` = `created_at` WHERE `updated_at` = 0');
    }

    public function down()
    {
        echo "m170131_073408_alter_columns_in_comments_table cannot be reverted.\n";

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
