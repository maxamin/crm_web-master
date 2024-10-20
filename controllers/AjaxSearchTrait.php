<?php

namespace app\controllers;


use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;

trait AjaxSearchTrait
{
    /**
     * @param $dataProvider ActiveDataProvider
     * @return mixed
     */
    protected function performAjaxSearch(ActiveDataProvider $dataProvider)
    {
        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {

            $dataProvider->setPagination([
                'pageSize' => 20,
            ]);

            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $dataProvider->models;
            Yii::$app->response->send();
            Yii::$app->end();
        }
    }
}