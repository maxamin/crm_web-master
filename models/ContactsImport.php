<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%contacts_import}}".
 *
 * @property integer $id
 * @property string | UploadedFile $file
 * @property integer $c_user_id
 * @property integer $created_at
 *
 * @property Contacts[] $contacts
 * @property Users $cUser
 */
class ContactsImport extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contacts_import}}';
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file', 'c_user_id'], 'required'],
            [['c_user_id', 'created_at'], 'integer'],
            [['file'], 'unique'],
            [['file'], 'file', 'extensions' => ['xls', 'xlsx'], 'checkExtensionByMimeType' => false],
            [['c_user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Users::className(), 'targetAttribute' => ['c_user_id' => 'id']],
        ];
    }

    public function init()
    {
        if ($this->isNewRecord) {
            $this->c_user_id = Yii::$app->user->id;
        }

        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'file' => 'Файл',
            'c_user_id' => 'Создатель',
            'created_at' => 'Создан',
        ];
    }

    public static function fileDirectory()
    {
        $directory = FileHelper::normalizePath(Yii::getAlias('@contacts-import-files'));

        FileHelper::createDirectory($directory, 0777, true);

        return $directory;
    }

    public function upload()
    {
        if ($this->validate()) {

            $directory = static::fileDirectory();

            $name = uniqid('f' . date('Y.m.d_H:i:s') . '-') . '.' . $this->file->extension;

            if ($this->file->saveAs($directory . '/' . $name)) {

                $this->file = $name;

                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getFilePath() : string
    {
        return static::fileDirectory() . '/' . $this->file;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contacts::className(), ['import_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'c_user_id']);
    }
}
