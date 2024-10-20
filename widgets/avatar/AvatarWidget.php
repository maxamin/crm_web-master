<?php

namespace app\widgets\avatar;

use app\models\Profile;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;


class AvatarWidget extends Widget
{
    /** @var  Profile profile */
    public $profile;

    /** @var string action url */
    public $action;

    public function init()
    {
        parent::init();

        if (!isset($this->profile) || !($this->profile instanceof Profile)) {
            $this->profile = Profile::find()->where(['user_id' => Yii::$app->user->getId()])->one();
        }
        $this->profile->setScenario('uploadAvatar');

        if (!isset($this->action)) {
            $this->action = Url::to('@settings-avatar-upload');
        }
    }

    /**
     * Lists all contacts Leads models.
     * @return mixed
     */
    public function run()
    {
        return $this->render('avatar', [
            'profile' => $this->profile,
            'action' => $this->action,
        ]);
    }
}