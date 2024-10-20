<?php

namespace app\models\forms;

use yii\helpers\ArrayHelper;

use dektrium\user\models\LoginForm as BaseLoginForm;

class LoginForm extends BaseLoginForm
{

    public function rules()
    {
        $rules = [
            'verificationValidate' => [
                'login',
                function ($attribute) {
                    if ($this->user !== null) {
                        if (!$this->user->getIsVerified()) {
                            $this->addError($attribute, 'Ваш аккаунт не подтвержден администратором');
                        }
                    }
                }
            ],
        ];

        return ArrayHelper::merge(parent::rules(), $rules);
    }
}