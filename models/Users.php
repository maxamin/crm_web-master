<?php

namespace app\models;

use app\models\queries\UsersQuery;
use dektrium\user\models\Token;
use dektrium\user\models\User as BaseUser;
use Yii;

/**
 * This is the model class for table "users".
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property integer $confirmed_at
 * @property string $unconfirmed_email
 * @property integer $blocked_at
 * @property string $registration_ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $verified_at
 * @property integer $flags
 * @property integer $city_id
 * @property integer $roles_id
 *
 * @property Comments[] $comments
 * @property Contacts[] $contacts
 * @property Contacts[] $contacts0
 * @property Events[] $events
 * @property Leads[] $leads
 * @property Leads[] $leads0
 * @property Profile $profile
 * @property SocialAccount[] $socialAccounts
 * @property Token[] $tokens
 * @property UsersFieldsValues[] $usersFieldsValues
 * @property UsersGroups[] $usersGroups
 * @property UsersRights[] $usersRights
 */
class Users extends BaseUser
{

    public function init()
    {
        parent::init();

        $this->on(self::AFTER_REGISTER, function ($e) {
            $e->sender->assign();
        });

        $this->on(self::AFTER_CREATE, function ($e) {
            $e->sender->assign();
        });
    }


    public function assign($role = null)
    {
        $auth = Yii::$app->authManager;

        if (!isset($role)) {

            switch ($this->profile->branch->is_main) {
                case 1:
                    $role = 'general';
                    break;
                default:
                    $role = 'manager';
            };
        }

        $role = $auth->getRole($role);

        $auth->assign($role, $this->id);

        return $this;
    }

    /**
     * Attempts user confirmation.
     *
     * @param string $code Confirmation code.
     *
     * @return boolean
     */
    public function attemptConfirmation($code)
    {
        $token = $this->finder->findTokenByParams($this->id, $code, Token::TYPE_CONFIRMATION);

        if ($token instanceof Token && !$token->isExpired) {
            $token->delete();
            if (($success = $this->confirm())) {
                $message = \Yii::t('user', 'Thank you, registration is now complete.');
            } else {
                $message = \Yii::t('user', 'Something went wrong and your account has not been confirmed.');
            }
        } else {
            $success = false;
            $message = \Yii::t('user', 'The confirmation link is invalid or expired. Please try requesting a new one.');
        }

        \Yii::$app->session->setFlash($success ? 'success' : 'danger', $message);

        return $success;
    }

    /**
     * @return bool Whether the user is verified or not.
     */
    public function getIsVerified()
    {
        return $this->verified_at != null;
    }

    /**
     * Verify the user by setting 'verified_at' field to current time.
     * @param bool $emailNotification
     * @return bool
     */
    public function verify($emailNotification = true)
    {
        $result = (bool)$this->updateAttributes([
            'verified_at' => time(),
        ]);

        if ($result && $emailNotification) {
            $this->mailer->sendVerificationMessage($this);
        }

        return $result;
    }

    /**
     * UnVerify the user by setting 'verified_at' field to null and regenerate `auth_key`.
     */
    public function unverify()
    {
        return (bool)$this->updateAttributes([
            'verified_at' => null,
            'auth_key'   => \Yii::$app->security->generateRandomString(),
        ]);
    }

    /**
     * @return bool Whether the user is an admin or not.
     */
    public function getIsAdmin()
    {
        return
            (\Yii::$app->getAuthManager() && $this->module->adminPermission ?
                \Yii::$app->getAuthManager()->checkAccess($this->id, $this->module->adminPermission) : false)
            || in_array($this->username, $this->module->admins);
    }

    /** @inheritdoc */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['c_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contacts::className(), ['c_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts0()
    {
        return $this->hasMany(Contacts::className(), ['r_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeads()
    {
        return $this->hasMany(Leads::className(), ['c_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeads0()
    {
        return $this->hasMany(Leads::className(), ['r_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }
}
