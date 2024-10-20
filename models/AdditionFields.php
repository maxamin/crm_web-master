<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "addition_fields".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $type
 * @property integer $active
 * @property string $name
 * @property integer $is_directory
 *
 * @property AdditionFieldsValues[] $additionFieldsValues
 * @property AdditionalValuesDirectories[] $additionalValuesDirectories
 */
class AdditionFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'addition_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'active', 'name'], 'required'],
            [['type', 'active', 'is_directory'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'type' => 'Type',
            'active' => 'Active',
            'name' => 'Name',
            'is_directory' => 'Is Directory',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionFieldsValues()
    {
        return $this->hasMany(AdditionFieldsValues::className(), ['addition_field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalValuesDirectories()
    {
        return $this->hasMany(AdditionalValuesDirectories::className(), ['addition_fields_id' => 'id']);
    }
}
