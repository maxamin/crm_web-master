<?php

namespace app\models;

use app\models\queries\NaturalQuery;
use yii\helpers\ArrayHelper;


class Natural extends Contacts
{

    private static $tableName = '{{%natural}}';

    public static function tableName()
    {
        return self::$tableName;
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            self::$tableName = parent::tableName();

            return true;
        } else {
            return false;
        }
    }

    public function init()
    {
        if (!($this instanceof ContactsSearch) && $this->isNewRecord) {
            $this->type = self::TYPE_NATURAL;
        }

        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = [
            'name' => 'Имя',
            'okpo' => 'ИНН',
        ];

        return ArrayHelper::merge(parent::attributeLabels(), $attributeLabels);
    }

    /**
     * @inheritdoc
     * @return NaturalQuery the active query used by this AR class.
     */
    public static function find($deleted = false)
    {
        return (new NaturalQuery(get_called_class()))->deleted($deleted);
    }
}
