<?php

namespace app\models;

use app\models\queries\BranchQuery;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "branch".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $is_main
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Profile[] $profiles
 */
class Branch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branch';
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'is_main', 'created_at', 'updated_at'], 'required'],
            [['is_main', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255, 'min' => 3],
            [['code'], 'string', 'max' => 32],
            [['name'], 'unique'],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'name' => 'Название',
            'code' => 'Код',
            'is_main' => 'Главное',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    public function getFullName()
    {
        return $this->name . ' (' . $this->code . ')';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['branch_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return BranchQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BranchQuery(get_called_class());
    }
}
