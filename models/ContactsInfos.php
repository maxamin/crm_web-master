<?php

namespace app\models;

use app\models\queries\ContactsInfosQuery;

/**
 * This is the model class for table "contacts_infos".
 *
 * @property string $id
 * @property string $contact_type_id
 * @property string $contact_id
 * @property string $contact_value
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Contacts $contact
 * @property ContactsInfoTypes $contactType
 */
class ContactsInfos extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacts_infos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_type_id'], 'required'],
            [['contact_type_id', 'contact_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['contact_value'], 'trim'],
            [['contact_value'], 'string', 'max' => 255],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contacts::className(), 'targetAttribute' => ['contact_id' => 'id']],
            [['contact_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContactsInfoTypes::className(), 'targetAttribute' => ['contact_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'contact_type_id' => 'Тип контакта',
            'contact_id' => 'Клиент',
            'contact_value' => 'Значение контакта',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }

    /**
     * @param bool $deleted
     * @return ContactsInfosQuery
     */
    public static function find($deleted = false)
    {
        return (new ContactsInfosQuery(get_called_class()))->deleted($deleted);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contacts::className(), ['id' => 'contact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactType()
    {
        return $this->hasOne(ContactsInfoTypes::className(), ['id' => 'contact_type_id']);
    }
}
