<?php

namespace app\models\forms;

use app\models\Branch;
use dektrium\user\models\Profile;
use dektrium\user\models\User;
use Yii;
use yii\helpers\ArrayHelper;

use dektrium\user\models\RegistrationForm as BaseRegistrationForm;

class RegistrationForm extends BaseRegistrationForm
{
    /**
     * @var string Profile name
     */
    public $name;

    /**
     * @var integer Profile branch_id
     */
    public $branch_id;

    public function rules()
    {

        $rules = [
            //name rules
            'nameLength'   => ['name', 'string', 'min' => 3, 'max' => 255],
            'nameTrim'     => ['name', 'filter', 'filter' => 'trim'],
            'nameRequired' => ['name', 'required'],

            //branch_id rules
            'branchExist' => ['branch_id', 'exist', 'skipOnError' => false, 'skipOnEmpty' => false, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
        ];

        return ArrayHelper::merge(parent::rules(), $rules);
    }

    public function attributeLabels()
    {
        $labels =  [
            'name'    => 'ФИО',
            'branch_id' => 'Отделение',
        ];

        return ArrayHelper::merge(parent::attributeLabels(), $labels);
    }

    /**
     * @inheritdoc
     */
    public function loadAttributes(User $user)
    {
        // here is the magic happens
        $user->setAttributes([
            'email'    => $this->email,
            'username' => $this->username,
            'password' => $this->password,
        ]);
        /** @var Profile $profile */
        $profile = Yii::createObject(Profile::className());
        $profile->setAttributes([
            'name' => $this->name,
            'branch_id' => $this->branch_id,
        ]);
        $user->setProfile($profile);
    }
}