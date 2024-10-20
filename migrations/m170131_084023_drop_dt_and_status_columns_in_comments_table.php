<?php

use yii\db\Migration;

class m170131_084023_drop_dt_and_status_columns_in_comments_table extends Migration
{
    public function up()
    {
        $this->dropColumn('comments', 'dt');
        $this->dropColumn('comments', 'status');
    }

    public function down()
    {
        echo "m170131_073408_alter_columns_in_comments_table cannot be reverted.\n";

        return false;
    }
}
