<?php

use yii\db\Migration;

/**
 * Handles the creation of table `department`.
 */
class m170301_125505_create_branch_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%branch}}', [
            'id' => $this->primaryKey(10)->unsigned(),
            'name' => $this->string(255)->notNull(),
            'code' => $this->string(32)->notNull(),
            'is_main' => $this->boolean()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('{{%branch_unique_name}}', '{{%branch}}', 'name', true);
        $this->createIndex('{{%branch_unique_code}}', '{{%branch}}', 'code', true);

        $this->addColumn('{{%profile}}', 'branch_id', $this->integer(10)->unsigned());

        $this->addForeignKey(
            '{{%fk_profile_branch}}',
            '{{%profile}}',
            'branch_id',
            '{{%branch}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%branch}}');
        $this->dropColumn('{{%profile}}', 'branch_id');
    }
}
