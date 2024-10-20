<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `active_from_leads`.
 */
class m170405_080257_drop_active_from_leads_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('leads', 'active');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('leads', 'active', $this->boolean());
    }
}
