<?php

use yii\db\Migration;

/**
 * Handles adding description to table `contacts`.
 */
class m170405_083834_add_description_column_to_contacts_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('contacts', 'description', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('contacts', 'description');
    }
}
