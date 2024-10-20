<?php

use yii\db\Migration;

class m170302_074729_insert_branches_to_branch_table extends Migration
{
    public function up()
    {
        $this->insert('{{%branch}}', [
            'name' => 'Главный офис',
            'code' => 'ГО',
            'is_main' => '1',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%branch}}', [
            'name' => 'Запорожское отделение №8',
            'code' => 'ЗП-8',
            'is_main' => '0',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%branch}}', [
            'name' => 'Катеринославское отделение №2',
            'code' => 'ДН-2',
            'is_main' => '0',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%branch}}', [
            'name' => 'Криворожское отделение №4',
            'code' => 'КРВ-4',
            'is_main' => '0',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%branch}}', [
            'name' => 'Одесское отделение №1',
            'code' => 'ОВ-1',
            'is_main' => '0',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%branch}}', [
            'name' => 'Одесское отделение №6',
            'code' => 'ОВ-6',
            'is_main' => '0',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%branch}}', [
            'name' => 'Стрелецкое отделение №7',
            'code' => 'ДН-7',
            'is_main' => '0',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%branch}}', [
            'name' => 'Львовская региональная дирекция',
            'code' => 'ЛВ-9',
            'is_main' => '0',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%branch}}', [
            'name' => 'Киевская региональная дирекция',
            'code' => 'КВ-100',
            'is_main' => '0',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%branch}}', [
            'name' => 'Днепропетровская региональная дирекция',
            'code' => 'ДН-10',
            'is_main' => '0',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function down()
    {
        echo "m170302_074729_insert_branches_to_branch_table cannot be reverted.\n";

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
