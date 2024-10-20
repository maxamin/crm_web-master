<?php

namespace app\widgets\leads;

use app\models\forms\LeadForm;
use app\models\Leads;
use Yii;
use yii\base\Widget;

class LeadsCreateWidget extends Widget
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

        if ($post = Yii::$app->request->post()) {

            $model->setAttributes($post);

            if ($model->save()) {

                $hide = true;

                $model = $this->initModelForm();
                return $this->render('create', [
                    'model' => $model,
                    'hide' => $hide,
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'hide' => $hide,
        ]);
    }

    /**
     * @return LeadForm
     */
    private function initModelForm()
    {
        $model = new LeadForm();
        $model->leads = new Leads();
        $model->leads->loadDefaultValues();
        $model->leads->contacts_id = $this->model->id;

        return $model;
    }
}