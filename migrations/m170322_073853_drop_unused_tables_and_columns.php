<?php

use yii\db\Migration;

class m170322_073853_drop_unused_tables_and_columns extends Migration
{
    public function up()
    {
        $this->dropColumn('contacts', 'parent_id');
        $this->dropColumn('contacts_infos', 'type');
        $this->dropColumn('user', 'roles_id');
        $this->dropTable('events');
        $this->dropTable('files');
        $this->dropTable('roles');
        $this->dropTable('tags_values');
        $this->dropTable('tags');
        $this->dropTable('users_fields_values');
        $this->dropTable('users_fields');
        $this->dropTable('users_groups');
        $this->dropTable('users_rights');
        $this->dropTable('groups');
        $this->dropTable('rights_values');
        $this->dropTable('rights');

    }

    public function down()
    {
        echo "m170322_073853_drop_unused_tables_and_columns cannot be reverted.\n";

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
