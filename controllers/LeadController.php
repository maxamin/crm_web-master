<?php

namespace app\controllers;

use app\models\forms\LeadForm;
use app\models\LeadsProducts;
use Yii;
use app\models\Leads;
use app\models\LeadsSearch;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
/**
 * LeadController implements the CRUD actions for Leads model.
 */
class LeadController extends Controller
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
                    'changeStatus' => ['POST'],
                    'changeProductStatus' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Leads models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LeadsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->performAjaxSearch($dataProvider);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Leads model.
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
     * Creates a new Leads model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LeadForm();
        $model->leads = new Leads();
        $model->leads->loadDefaultValues();

        if ($post = Yii::$app->request->post()) {

            $model->setAttributes($post);

            if ($model->save()) {

                Yii::$app->getSession()->setFlash('success', 'Сделка <b>"'.$model->leads->getIdentifier().'"</b> успешно создана.');
                return $this->redirect(['view', 'id' => $model->leads->id]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Leads model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new LeadForm();
        $model->leads = $this->findModel($id);

        if ($post = Yii::$app->request->post()) {

            $model->setAttributes($post);

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Сделка <b>"'.$model->leads->getIdentifier().'"</b> успешно обновлена.');
                return $this->redirect(['view', 'id' => $model->leads->id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }


    /**
     * Deletes an existing Leads model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        Yii::$app->getSession()->setFlash('success', 'Сделка <b>'.$model->getIdentifier().'</b> успешно удалена.');

        if (strpos(Yii::$app->request->referrer, Url::to(['view'])) !== false) {
            return $this->redirect(['index']);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * @param $status
     * @return mixed
     */
    public function actionChangeStatus($id, $status)
    {
        $model = $this->findModel($id);

        if ($model->changeStatus($status)) {
            $msgStatus = 'success';
            $message = 'Статус успешно изменен.';
        } else {
            $msgStatus = 'error';
            $message = implode(',', $model->getFirstErrors());
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderJSON($msgStatus, $message);
        }

        Yii::$app->getSession()->setFlash($msgStatus, $message);
        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * @param $id
     * @param $status
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionChangeProductStatus($id, $status)
    {
        $model = LeadsProducts::findOne($id);

        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model->status = $status;

        if ($model->save()) {
            $msgStatus = 'success';
            $message = 'Статус успешно изменен.';
        } else {
            $msgStatus = 'error';
            $message = implode(',', $model->getErrors());
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderJSON($msgStatus, $message);
        }

        Yii::$app->getSession()->setFlash($msgStatus, $message);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Leads model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|\yii\db\ActiveRecord
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $query = Leads::find()->where([Leads::tableName() . '.id' => $id]);

        if (!Yii::$app->user->can('manageAllLeads')) {
            $query->onlyOwnBranch();
        }

        $model = $query->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
