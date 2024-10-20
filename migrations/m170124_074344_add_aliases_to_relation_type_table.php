<?php

use yii\db\Migration;

class m170124_074344_add_aliases_to_relation_type_table extends Migration
{
    public function up()
    {
        $this->addColumn('relation_types', 'alias', $this->string()->notNull());
        $this->execute("UPDATE `relation_types` SET `name` = 'Лицо' WHERE `name` = 'Контакты'");
        $this->execute("UPDATE `relation_types` SET `alias` = `table`");
        $this->alterColumn('relation_types', 'alias', $this->string()->notNull()->unique());
    }

    public function down()
    {
        $this->dropColumn('relation_types', 'alias');
    }
}
