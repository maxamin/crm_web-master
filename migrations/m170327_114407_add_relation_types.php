<?php

use yii\db\Migration;

class m170327_114407_add_relation_types extends Migration
{
    public function up()
    {
        $this->insert('relation_types',
            ['table' => 'contacts', 'name' => 'Физ.лицо', 'class_name' => \app\models\Natural::className(), 'alias' => 'natural']
        );

        $this->insert('relation_types',
            ['table' => 'contacts', 'name' => 'Юр.лицо', 'class_name' => \app\models\Legal::className(), 'alias' => 'legal']
        );
    }

    public function down()
    {
        $this->delete('relation_types', [
            'class_name' => \app\models\Natural::className(),
        ]);

        $this->delete('relation_types', [
            'class_name' => \app\models\Legal::className(),
        ]);
    }
}
