<?php

namespace app\controllers;

use app\framework\filters\AccessRule;
use app\models\ContactsLink;
use app\models\forms\NaturalForm;
use app\models\forms\NaturalImportForm;
use Yii;
use app\models\Natural;
use app\models\NaturalSearch;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * NaturalController implements the CRUD actions for Natural model.
 */
class NaturalController extends Controller
{
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
                    'class' => AccessRule::className()
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
                                'Вы можете управлять только физическими лицами своего отделения.');

                            $action = Yii::$app->request->referrer ?? 'index';

                            return $this->redirect($action);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['import'],
                        'roles' => ['manager'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('importContacts');
                        },
                        'denyCallback' => function ($rule, $action) {

                            if (Yii::$app->user->isGuest) {
                                return Yii::$app->user->loginRequired();
                            }

                            Yii::$app->getSession()->setFlash('warning',
                                'У вас нет прав на импорт контактов.');

                            $action = Yii::$app->request->referrer ?? 'index';

                            return $this->redirect($action);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Natural models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NaturalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->performAjaxSearch($dataProvider);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Natural model.
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
     * Creates a new Natural model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NaturalForm();
        $model->natural = new Natural();
        $model->natural->loadDefaultValues();

        $post = Yii::$app->request->post();

        $model->setAttributes($post);

        if ($post && $model->save()) {
            Yii::$app->getSession()->setFlash('success',
                'Физическое лицо <b>"' . $model->natural->name . '"</b> успешно создано.');
            return $this->redirect(['view', 'id' => $model->natural->id]);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Natural model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new NaturalForm();
        $model->natural = $this->findModel($id);

        $post = Yii::$app->request->post();

        $model->setAttributes($post);

        if ($post && $model->save()) {
            Yii::$app->getSession()->setFlash('success',
                'Физическое лицо <b>"' . $model->natural->name . '"</b> успешно обновлено.');
            return $this->redirect(['view', 'id' => $model->natural->id]);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing Natural model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        Yii::$app->getSession()->setFlash('success', 'Физическое лицо <b>"' . $model->name . '"</b> успешно удалено.');

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
     * @return string
     */
    public function actionImport()
    {
        $model = new NaturalImportForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Физические лица успешно импортированы.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('import', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Natural model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Natural the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Natural::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
