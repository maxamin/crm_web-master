<?php

namespace app\widgets\tasks;

use app\models\RelationTypes;
use app\models\Tasks;
use Yii;
use yii\base\Widget;

class TasksCreateWidget extends Widget
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
        $hide = Yii::$app->request->isGet;

        $model = $this->initModelForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $hide = true;

            $model = $this->initModelForm();
        }

        return $this->render('create', [
            'model' => $model,
            'hide' => $hide,
        ]);
    }

    /**
     * @return Tasks
     */
    private function initModelForm()
    {
        $model = new Tasks();
        $model->loadDefaultValues();
        $model->relatedWith($this->model);

        return $model;
    }
}