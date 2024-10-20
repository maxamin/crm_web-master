<?php

namespace app\controllers;

use Yii;
use app\models\Tasks;
use app\models\TasksSearch;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Tasks model.
 */
class TaskController extends Controller
{
    use RenderJSONTrait;
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
                    'close' => ['POST'],
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
     * Lists all Tasks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TasksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tasks model.
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
     * Creates a new Tasks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tasks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Задача <b>'.$model->type->name.' - '. $model->related->getIdentifier().'</b> успешно создана.');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tasks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Задача <b>'.$model->type->name.' - '. $model->related->getIdentifier().'</b> успешно обновлена.');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tasks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        Yii::$app->getSession()->setFlash('success', 'Задача <b>'.$model->type->name.' - '. $model->related->getIdentifier().'</b> успешно удалена.');

        if (strpos(Yii::$app->request->referrer, Url::to(['view'])) !== false) {
            return $this->redirect(['index']);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Изменение статуса задачи на закрыта
     * @param $id
     * @return array|\yii\web\Response
     */
    public function actionClose($id)
    {
        $model = $this->findModel($id);

        $data = [];

        if ($model->close(Yii::$app->request->post('close_comment'))) {
            $msgStatus = 'success';
            $message = 'Задача успешно закрыта.';
            $data = [
                'closeUser' => Html::a($model->closeUser->profile->name,
                    ['@user-profile', 'id' => $model->closeUser->id],
                    ['data-pjax' => '0']
                )
            ];
        } else {
            $msgStatus = 'error';
            $message = $model->getFirstError('close_comment');
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderJSON($msgStatus, $message, $data);
        }

        Yii::$app->getSession()->setFlash($msgStatus, $message);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Tasks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|\yii\db\ActiveRecord
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $query = Tasks::find()->where([Tasks::tableName() . '.id' => $id]);

        if (!Yii::$app->user->can('manageAllTasks')) {
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
