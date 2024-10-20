<?php


namespace app\models\forms;


use app\models\Branch;
use app\models\Profile;
use app\models\Users;
use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;

class UserForm extends Model
{
    use ModuleTrait;

    /**
     * @var Users user
     */
    public $user;

    /**
     * @var string User email address
     */
    public $email;

    /**
     * @var string Username
     */
    public $username;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var string Profile name
     */
    public $name;

    /**
     * @var integer Profile branch_id
     */
    public $branch_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $user = $this->module->modelMap['User'];

        return [
            // username rules
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 255],
            'usernameTrim'     => ['username', 'filter', 'filter' => 'trim'],
            'usernamePattern'  => ['username', 'match', 'pattern' => $user::$usernameRegexp],
            'usernameRequired' => ['username', 'required'],
            'usernameUnique'   => [
                'username',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('user', 'This username has already been taken')
            ],
            // email rules
            'emailTrim'     => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern'  => ['email', 'email'],
            'emailUnique'   => [
                'email',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('user', 'This email address has already been taken')
            ],
            // password rules
            'passwordLength'   => ['password', 'string', 'min' => 6, 'max' => 72],
            //name rules
            'nameLength'   => ['name', 'string', 'min' => 3, 'max' => 255],
            'nameTrim'     => ['name', 'filter', 'filter' => 'trim'],
            'nameRequired' => ['name', 'required'],
            //branch_id rules
            'branchExist' => ['branch_id', 'exist', 'skipOnError' => false, 'skipOnEmpty' => false, 'targetClass' =>
                Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'    => Yii::t('user', 'Email'),
            'username' => Yii::t('user', 'Username'),
            'password' => Yii::t('user', 'Password'),
            'name'    => 'ФИО',
            'branch_id' => 'Отделение',
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'create-form';
    }

    /**
     * Registers a new user account. If registration was successful it will set flash message.
     *
     * @return bool
     */
    public function create()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var Users $user */
        $this->user = Yii::createObject(Users::className());
        $this->user->setScenario('create');
        $this->loadAttributes($this->user);

        if (!$this->user->create()) {
            return false;
        }

        if (!$this->user->verify(false)) {
            Yii::$app->session->setFlash('info',
                'Не удалось автоматически подтвердить пользователя.');
        }

        return true;
    }

    /**
     * @param Users $user
     */
    protected function loadAttributes(Users $user)
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