<?php

namespace app\controllers;

use app\models\LeadsSearch;
use app\models\LegalSearch;
use app\models\NaturalSearch;
use app\models\TasksSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class StatisticController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['general'],
                    ],
                ],
            ],
        ];
    }

    public function actionLegal()
    {
        $searchModel = new LegalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('legal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionNatural()
    {
        $searchModel = new NaturalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('natural', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLeads()
    {
        $searchModel = new LeadsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('leads', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTasks()
    {
        $searchModel = new TasksSearch();
        $searchModel->day = TasksSearch::ALL;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('tasks', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}