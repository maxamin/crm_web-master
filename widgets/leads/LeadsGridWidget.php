<?php

namespace app\widgets\leads;

use app\models\LeadsSearch;
use Yii;
use yii\base\Widget;

class LeadsGridWidget extends Widget
{
    /* @var $model \app\models\Contacts */
    public $model;

    public function init()
    {
        parent::init();
    }

    /**
     * Lists all contacts Leads models.
     * @return mixed
     */
    public function run()
    {
        $searchModel = new LeadsSearch();
        $searchModel->conid = $this->model->id;

        $dataProvider = $searchModel->search(Yii::$app->request->post());
        $dataProvider->setPagination([
            'pageSize' => '10',
        ]);

        return $this->render('grid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}