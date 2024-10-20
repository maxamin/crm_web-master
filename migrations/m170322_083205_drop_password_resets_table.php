<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `password_resets`.
 */
class m170322_083205_drop_password_resets_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('password_resets');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "m170322_073853_drop_unused_tables_and_columns cannot be reverted.\n";

        return false;
    }
}
