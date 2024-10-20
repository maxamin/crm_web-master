<?php

use yii\db\Migration;

/**
 * Handles the creation of table `contacts_import`.
 */
class m171011_061153_create_contacts_import_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%contacts_import}}', [
            'id' => $this->primaryKey(),
            'file' => $this->string(255)->notNull(),
            'c_user_id' => $this->integer(10)->unsigned()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            '{{%fk_contacts_import_user}}',
            '{{%contacts_import}}',
            'c_user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->createIndex('{{%contacts_import_unique_file}}', '{{%contacts_import}}', 'file', true);

        $this->addColumn('{{%contacts}}', 'import_id', $this->integer());

        $this->addForeignKey(
            '{{%fk_contacts_contacts_import}}',
            '{{%contacts}}',
            'import_id',
            '{{%contacts_import}}',
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
        $this->dropTable('{{%contacts_import}}');
    }
}
