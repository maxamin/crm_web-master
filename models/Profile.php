<?php

namespace app\models;

use app\models\queries\ProfileQuery;
use borales\extensions\phoneInput\PhoneInputValidator;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\helpers\ArrayHelper;
use Yii;

use dektrium\user\models\Profile as BaseProfile;
use yii\helpers\FileHelper;
use yii\helpers\Url;

use yii\imagine\Image;
use yii\web\UploadedFile;

class Profile extends BaseProfile
{

    const AVATAR_WIDTH = 200;
    const AVATAR_HEIGHT = 200;

    /** @var UploadedFile */
    private $avatarFile;


    /* crop */
    private $x1;
    private $x2;
    private $y1;
    private $y2;

    public function rules()
    {
        $rules = parent::rules();

        $rules['nameLength'] = ['name', 'string', 'min' => 3, 'max' => 255];
        $rules['nameTrim'] = ['name', 'filter', 'filter' => 'trim'];
        $rules['nameRequired'] = ['name', 'required'];

        $rules['nameLength'] = ['name', 'string', 'min' => 3, 'max' => 255];
        $rules['nameTrim'] = ['name', 'filter', 'filter' => 'trim'];
        $rules['nameRequired'] = ['name', 'required'];

        $rules['contactPhoneTrim'] = ['contact_phone', 'filter', 'filter' => 'trim'];
        $rules['contactPhoneValidator'] = ['contact_phone', PhoneInputValidator::class, 'region' => ['UA']];
        $rules['contactPhoneFilter'] = ['contact_phone', 'filter', 'filter' => function ($value) {
            return preg_replace('/[^+0-9]/', '', $value);
        }];
        $rules['contactPhoneLength'] = ['contact_phone', 'string', 'length' => [13, 13]];
        $rules['contactPhoneInteger'] = ['contact_phone', 'integer'];
        $rules['contactPhoneDefault'] = ['contact_phone', 'default', 'value' => null];

        $rules['internalPhoneTrim'] = ['internal_phone', 'filter', 'filter' => 'trim'];
        $rules['internalPhoneLength'] = ['internal_phone', 'string', 'length' => [4, 4]];
        $rules['internalPhoneInteger'] = ['internal_phone', 'integer'];
        $rules['internalPhoneDefault'] = ['internal_phone', 'default', 'value' => null];

        $rules['avatarFileImage'] = ['avatarFile', 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg',
            'on' => ['uploadAvatar']];

        $rules['cropCordsInteger'] = [['x1', 'x2', 'y1', 'y2'], 'integer'];

        $rules['branchExist'] = ['branch_id', 'exist', 'skipOnError' => false, 'skipOnEmpty' => false,
            'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = [
            'name' => 'ФИО',
            'branch_id' => 'Отделение',
            'contact_phone' => 'Контактный телефон',
            'internal_phone' => 'Внутренний телефон',
            'public_email' => 'Контактный email',
            'avatarFile' => 'Аватар',
            'avatar' => 'Аватар',
        ];

        return ArrayHelper::merge(parent::attributeLabels(), $labels);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            'uploadAvatar' => ['avatarFile', 'x1', 'x2', 'y1', 'y2'],
        ];

        return ArrayHelper::merge(parent::scenarios(), $scenarios);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }


    /**
     * @param int $size
     * @return string Avatar url
     */
    public function getAvatarUrl($size = 200)
    {
        $webPath = Yii::$app->params['defaultProfileAvatar'];

        if ($this->avatar != null) {

            $filePath = FileHelper::normalizePath(Yii::getAlias('@users-files-app') . '/' . $this->user_id
                . '/' . $this->avatar);

            if (file_exists($filePath)) {
                $webPath = Url::to('@users-files/' .$this->user_id . '/' . $this->avatar);
            }
        }

        return $webPath;
    }

    /**
     * return contact email
     * @return string
     */
    public function getContactEmail()
    {
        return $this->public_email != null ? $this->public_email : $this->user->email;
    }

    /**
     * @param int $width
     * @param int $height
     * @param int $quality
     * @return bool
     */
    public function uploadAvatar($width = self::AVATAR_WIDTH, $height = self::AVATAR_HEIGHT, $quality = 80)
    {
        if ($this->validate()) {

            $directory = FileHelper::normalizePath(Yii::getAlias('@users-files-app') . '/' . $this->user_id);

            FileHelper::createDirectory($directory, 0777, true);

            $name =  uniqid('a' . date('Y.m.d_H:i:s') . '-') . '.' . $this->avatarFile->extension;

            $img = Image::getImagine()->open($this->avatarFile->tempName);

            $crop = $this->calcCrop($img->getSize()->getWidth(), $img->getSize()->getHeight());

            $img->crop(new Point($crop['x'], $crop['y']), new Box($crop['width'], $crop['height']))
                ->resize(new Box($width, $height));

            if ($img->save($directory . '/' . $name, ['quality' => $quality])) {

                $this->avatar = $name;

                return true;
            }
        }

        return false;
    }

    private function calcCrop($width, $height)
    {
        $params = [];

        if (isset($this->x1) && isset($this->x2) && isset($this->y1) && isset($this->y2)) {

            $params['width'] = $this->x2 - $this->x1;
            $params['height'] = $this->y2 - $this->y1;

            $params['x'] = $this->x1;
            $params['y'] = $this->y1;

        } else {

            $params['width'] = min($width, $height);
            $params['height'] = $params['width'];

            $params['x'] = round(($width - $params['width']) / 2);
            $params['y'] = round(($height - $params['height']) / 2);
        }

        return $params;
    }

    /** @inheritdoc */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }

    /**
     * @return mixed
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @param mixed $avatarFile
     */
    public function setAvatarFile($avatarFile)
    {
        $this->avatarFile = $avatarFile;
    }

    /**
     * @return mixed
     */
    public function getX1()
    {
        return $this->x1;
    }

    /**
     * @param mixed $x1
     */
    public function setX1($x1)
    {
        $this->x1 = $x1;
    }

    /**
     * @return mixed
     */
    public function getX2()
    {
        return $this->x2;
    }

    /**
     * @param mixed $x2
     */
    public function setX2($x2)
    {
        $this->x2 = $x2;
    }

    /**
     * @return mixed
     */
    public function getY1()
    {
        return $this->y1;
    }

    /**
     * @param mixed $y1
     */
    public function setY1($y1)
    {
        $this->y1 = $y1;
    }

    /**
     * @return mixed
     */
    public function getY2()
    {
        return $this->y2;
    }

    /**
     * @param mixed $y2
     */
    public function setY2($y2)
    {
        $this->y2 = $y2;
    }
}
