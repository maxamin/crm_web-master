<?php

namespace app\controllers;

use app\framework\filters\AccessRule;
use app\models\ContactsLink;
use app\models\forms\LegalForm;
use Yii;
use app\models\Legal;
use app\models\LegalSearch;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LegalController implements the CRUD actions for Legal model.
 */
class LegalController extends Controller
{
    use RenderJSONTrait;
    use AjaxSearchTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'unlink' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' =>   AccessRule::className()
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'unlink'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'update', 'delete'],
                        'roles' => ['manager'],
                        'matchCallback' => function ($rule, $action) {

                            return Yii::$app->user->can('manageContact', [
                                'contact' => $this->findModel(Yii::$app->request->get('id')),
                            ]);
                        },
                        'denyCallback' => function ($rule, $action) {

                            if (Yii::$app->user->isGuest) {
                                return Yii::$app->user->loginRequired();
                            }

                            Yii::$app->getSession()->setFlash('warning',
                                'Вы можете управлять только юридическими лицами своего отделения.');

                            $action = Yii::$app->request->referrer ?? 'index';

                            return $this->redirect($action);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Legal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LegalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->performAjaxSearch($dataProvider);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Legal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Legal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LegalForm();
        $model->legal = new Legal();
        $model->legal->loadDefaultValues();

        $post = Yii::$app->request->post();

        $model->setAttributes($post);

        if ($post && $model->save()) {
            Yii::$app->getSession()->setFlash('success',
                'Юридическое лицо <b>"'.$model->legal->name.'"</b> успешно создано.');
            return $this->redirect(['view', 'id' => $model->legal->id]);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Legal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new LegalForm();
        $model->legal = $this->findModel($id);

        $post = Yii::$app->request->post();

        $model->setAttributes($post);

        if ($post && $model->save()) {
            Yii::$app->getSession()->setFlash('success',
                'Юридическое лицо <b>"'.$model->legal->name.'"</b> успешно обновлено.');
            return $this->redirect(['view', 'id' => $model->legal->id]);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing Legal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        Yii::$app->getSession()->setFlash('success', 'Юридическое лицо <b>"'.$model->name.'"</b> успешно удалено.');

        if (strpos(Yii::$app->request->referrer, Url::to(['view'])) !== false) {
            return $this->redirect(['index']);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUnlink($id)
    {
        $link = ContactsLink::findOne($id);

        if ($link == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $message = 'Связь успешно удалена.';

        $link->delete();

        if (Yii::$app->request->isAjax) {
            return $this->renderJSON('success', $message);
        }

        Yii::$app->getSession()->setFlash('success', $message);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Legal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Legal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Legal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
