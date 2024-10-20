<?php

namespace app\widgets\tasks;

use app\models\TasksSearch;
use Yii;
use yii\base\Widget;

class TasksGridWidget extends Widget
{

    const OPTIONS = [
        'day' => TasksSearch::ALL,
    ];

    /* @var $model \app\models\Contacts */
    public $model = null;
    public $options = array();

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
        $searchModel = $this->initSearchModel();

        if (isset($this->model)) {
            $searchModel->relatedWith($this->model);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->post());
        $dataProvider->setPagination([
            'pageSize' => '10',
        ]);

        return $this->render('grid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function initSearchModel()
    {
        $searchModel = new TasksSearch();

        $options = array_merge(self::OPTIONS, $this->options);

        foreach ($options as $key => $value) {
            $searchModel->{$key} = $value;
        }

        return $searchModel;
    }
}