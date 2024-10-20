<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `name_from_leads`.
 */
class m170331_131629_drop_name_from_leads_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('leads', 'name');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('leads', 'name', $this->string());
    }
}
