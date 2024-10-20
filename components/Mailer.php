<?php
/**
 * Created by PhpStorm.
 * User: maxch
 * Date: 07.04.17
 * Time: 17:00
 */

namespace app\components;

use dektrium\user\Mailer as BaseMailer;
use dektrium\user\models\User;
use Yii;

class Mailer extends BaseMailer
{

    /** @var string */
    protected $verificationSubject;

    /**
     * Sends an email to a user after verification by admin.
     *
     * @param User  $user
     *
     * @return bool
     */
    public function sendVerificationMessage(User $user)
    {
        return $this->sendMessage(
            $user->email,
            $this->getVerificationSubject(),
            'verification',
            ['user' => $user, 'module' => $this->module]
        );
    }

    public function getVerificationSubject()
    {
        if ($this->verificationSubject == null) {
            $this->setVerificationSubject('Подтверждение администратором ' . Yii::$app->name);
        }

        return $this->verificationSubject;
    }

    /**
     * @param string $verificationSubject
     */
    public function setVerificationSubject($verificationSubject)
    {
        $this->verificationSubject = $verificationSubject;
    }
}