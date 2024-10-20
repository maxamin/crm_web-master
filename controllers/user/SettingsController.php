<?php

namespace app\controllers\user;
use dektrium\user\controllers\SettingsController as BaseSettingsController;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class SettingsController extends BaseSettingsController
{

    /** @inheritdoc */
    public function behaviors()
    {
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'upload-avatar' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['upload-avatar'],
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];

        return ArrayHelper::merge(parent::behaviors(), $behaviors);
    }


    public function actionUploadAvatar()
    {
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());
        $model->setScenario('uploadAvatar');

        $model->load(Yii::$app->request->post());
        $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');

        if ($model->uploadAvatar() && $model->save()) {

            Yii::$app->getSession()->setFlash('success', 'Ваш аватар успешно обновлен.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        Yii::$app->getSession()->setFlash('error', implode(',', $model->getFirstErrors()));
        return $this->redirect(Yii::$app->request->referrer);
    }
}