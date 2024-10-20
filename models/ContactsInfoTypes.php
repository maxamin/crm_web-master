<?php

namespace app\models;

use sjaakp\sortable\Sortable;
use Yii;

/**
 * This is the model class for table "contacts_info_types".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $ord
 *
 * @property ContactsInfos[] $contactsInfos
 */
class ContactsInfoTypes extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        $behaviors = [
            Sortable::className(),
        ];

        return array_merge($behaviors, parent::behaviors());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacts_info_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'required'],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactsInfos()
    {
        return $this->hasMany(ContactsInfos::className(), ['contact_type_id' => 'id']);
    }
}
