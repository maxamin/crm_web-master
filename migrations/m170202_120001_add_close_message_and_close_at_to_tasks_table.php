<?php

use yii\db\Migration;

class m170202_120001_add_close_message_and_close_at_to_tasks_table extends Migration
{
    public function up()
    {
        $this->addColumn('tasks', 'close_comment', $this->text());
        $this->addColumn('tasks', 'closed_at', 'TIMESTAMP NULL');
    }

    public function down()
    {
        $this->dropColumn('tasks', 'close_commment');
        $this->dropColumn('tasks', 'closed_at');
    }
}
