<?php

namespace app\models;

use app\models\queries\ContactsLinkQuery;

/**
 * This is the model class for table "contacts_link".
 *
 * @property integer $id
 * @property string $contacts_id
 * @property string $link_id
 * @property integer $type
 *
 * @property Contacts $link
 * @property Contacts $contacts
 */
class ContactsLink extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacts_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link_id'], 'required'],
            [['id', 'contacts_id', 'link_id', 'type'], 'integer'],
            [['link_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contacts::className(), 'targetAttribute' => ['link_id' => 'id']],
            [['contacts_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contacts::className(), 'targetAttribute' => ['contacts_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contacts_id' => 'Contacts ID',
            'link_id' => 'Link ID',
            'type' => 'Type',
        ];
    }

    public static function find($deleted = false)
    {
        return (new ContactsLinkQuery(get_called_class()))->deleted($deleted);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Contacts::className(), ['id' => 'link_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasOne(Contacts::className(), ['id' => 'contacts_id']);
    }
}
