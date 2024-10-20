<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "additional_values_directories".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $addition_fields_id
 * @property integer $value
 * @property string $name
 *
 * @property AdditionFields $additionFields
 */
class AdditionalValuesDirectories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'additional_values_directories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['addition_fields_id', 'value', 'name'], 'required'],
            [['addition_fields_id', 'value'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['addition_fields_id'], 'exist', 'skipOnError' => true, 'targetClass' => AdditionFields::className(), 'targetAttribute' => ['addition_fields_id' => 'id']],
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
            'addition_fields_id' => 'Addition Fields ID',
            'value' => 'Value',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionFields()
    {
        return $this->hasOne(AdditionFields::className(), ['id' => 'addition_fields_id']);
    }
}
