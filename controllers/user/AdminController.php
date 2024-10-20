<?php

namespace app\controllers\user;

use app\models\forms\UserForm;
use app\models\Users;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Url;
use dektrium\user\controllers\AdminController as BaseAdminController;
use yii\web\UploadedFile;


class AdminController extends BaseAdminController
{
    /** @inheritdoc */
    public function behaviors()
    {
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'verify' => ['post'],
                    'upload-avatar' => ['post'],
                ],
            ],
        ];

        return ArrayHelper::merge(parent::behaviors(), $behaviors);
    }

    /**
     * Verify the user.
     *
     * @param int $id
     *
     * @return Response
     */
    public function actionVerify($id)
    {
        if ($id == \Yii::$app->user->getId()) {
            \Yii::$app->getSession()->setFlash('danger',
                'Вы не можете изменять статус подтверждения своего собственного аккаунта');
        } else {
            /* @var $user Users */
            $user  = $this->findModel($id);
            if (!$user->getIsVerified()) {
                $user->verify();
                \Yii::$app->getSession()->setFlash('success', 'Пользователь подтвержден');
            } else {
                $user->unverify();
                \Yii::$app->getSession()->setFlash('success', 'Подтверждение пользователя отменено');
            }
        }

        return $this->redirect(Url::previous('actions-redirect'));
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        /** @var UserForm $model */
        $model = \Yii::createObject(UserForm::className());
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_CREATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->create()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been created'));
            $this->trigger(self::EVENT_AFTER_CREATE, $event);
            return $this->redirect(['update', 'id' => $model->user->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Response
     */
    public function actionUploadAvatar($id)
    {
        $model = $this->finder->findProfileById($id);
        $model->setScenario('uploadAvatar');

        $model->load(Yii::$app->request->post());
        $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');

        if ($model->uploadAvatar() && $model->save()) {

            Yii::$app->getSession()->setFlash('success', 'Аватар успешно обновлен.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        Yii::$app->getSession()->setFlash('error', implode(',', $model->getFirstErrors()));
        return $this->redirect(Yii::$app->request->referrer);
    }
}