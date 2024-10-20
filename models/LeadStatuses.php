<?php

namespace app\models;

use app\models\queries\LeadStatusesQuery;
use Yii;

/**
 * This is the model class for table "lead_statuses".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property integer $ord
 */
class LeadStatuses extends \yii\db\ActiveRecord
{

    const IN_WORK = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lead_statuses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'ord'], 'required'],
            [['ord'], 'integer'],
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
            'name' => 'Name',
            'ord' => 'Ord',
        ];
    }

    public static function find()
    {
        return new LeadStatusesQuery(get_called_class());
    }
}
