<?php

namespace app\models;

use app\models\queries\RelationTypesQuery;
use Yii;

/**
 * This is the model class for table "relation_types".
 *
 * @property integer $id
 * @property string $table
 * @property string $name
 */
class RelationTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relation_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_name'], 'required'],
            [['table', 'name', 'class_name', 'alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'table' => 'Таблица',
            'name' => 'Имя',
            'class_name' => 'Имя класса',
            'alias' => 'Алиас',
        ];
    }

    /** @inheritdoc */
    public static function find()
    {
        return new RelationTypesQuery(get_called_class());
    }
}
