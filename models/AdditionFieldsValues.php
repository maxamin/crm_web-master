<?php

namespace app\models;

use app\models\queries\AdditionFieldsValuesQuery;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "addition_fields_values".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $addition_field_id
 * @property string $foregin_table_id
 * @property string $value
 *
 * @property AdditionFields $additionField
 */
class AdditionFieldsValues extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;

    const SCENARIO_LEGAL = 'legal';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'addition_fields_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['addition_field_id'], 'required'],
            [['addition_field_id', 'foregin_table_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['value'], 'trim'],
            [['value'], 'required', 'on' => self::SCENARIO_LEGAL],
            [['addition_field_id'], 'exist', 'skipOnError' => true, 'targetClass' => AdditionFields::className(), 'targetAttribute' => ['addition_field_id' => 'id']],
        ];
    }

    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_LEGAL,
        ];

        return ArrayHelper::merge(parent::scenarios(), $scenarios);
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
            'addition_field_id' => 'Addition Field ID',
            'foregin_table_id' => 'Foregin Table ID',
            'value' => 'Дополнительный параметр',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            /* save only if field value is not empty */
            if (!empty($this->value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function find($deleted = false)
    {
        return (new AdditionFieldsValuesQuery(get_called_class()))->deleted($deleted);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionField()
    {
        return $this->hasOne(AdditionFields::className(), ['id' => 'addition_field_id']);
    }
}
