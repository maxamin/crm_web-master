<?php

namespace app\models;

use app\models\queries\LegalQuery;
use Yii;
use yii\helpers\ArrayHelper;


class Legal extends Contacts
{
    private static $tableName = '{{%legal}}';

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
            $this->type = self::TYPE_LEGAL;
        }

        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = [
            'name' => 'Название',
            'okpo' => 'ОКПО',
        ];

        return ArrayHelper::merge(parent::attributeLabels(), $attributeLabels);
    }

    /**
     * @inheritdoc
     * @return LegalQuery the active query used by this AR class.
     */
    public static function find($deleted = false)
    {
        return (new LegalQuery(get_called_class()))->deleted($deleted);
    }
}
