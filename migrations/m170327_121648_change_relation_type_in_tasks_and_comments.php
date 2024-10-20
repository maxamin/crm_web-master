<?php

use yii\db\Migration;

class m170327_121648_change_relation_type_in_tasks_and_comments extends Migration
{
    public function up()
    {
        $this->execute('UPDATE tasks t INNER JOIN relation_types rt ON t.relation_type = rt.id AND
         rt.alias = "contacts" LEFT JOIN contacts cs ON t.relation_id = cs.id
          SET t.relation_type = (SELECT id FROM relation_types WHERE alias = "legal")
           WHERE cs.type = '. \app\models\Contacts::TYPE_LEGAL);

        $this->execute('UPDATE tasks t INNER JOIN relation_types rt ON t.relation_type = rt.id AND
         rt.alias = "contacts" LEFT JOIN contacts cs ON t.relation_id = cs.id
          SET t.relation_type = (SELECT id FROM relation_types WHERE alias = "natural")
           WHERE cs.type = '. \app\models\Contacts::TYPE_NATURAL);

        $this->execute('UPDATE comments t INNER JOIN relation_types rt ON t.relation_type = rt.id AND
         rt.alias = "contacts" LEFT JOIN contacts cs ON t.relation_id = cs.id
          SET t.relation_type = (SELECT id FROM relation_types WHERE alias = "legal")
           WHERE cs.type = '. \app\models\Contacts::TYPE_LEGAL);

        $this->execute('UPDATE comments t INNER JOIN relation_types rt ON t.relation_type = rt.id AND
         rt.alias = "contacts" LEFT JOIN contacts cs ON t.relation_id = cs.id
          SET t.relation_type = (SELECT id FROM relation_types WHERE alias = "natural")
           WHERE cs.type = '. \app\models\Contacts::TYPE_NATURAL);
    }

    public function down()
    {
        echo "m170327_121648_change_relation_type_in_tasks_and_comments cannot be reverted.\n";

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
