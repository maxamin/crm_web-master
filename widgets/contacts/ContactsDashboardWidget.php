<?php

namespace app\widgets\contacts;

use app\models\Contacts;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class ContactsDashboardWidget extends Widget
{

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
        $query = Contacts::find();

        if (! Yii::$app->user->can('manageAllContacts')) {
            $query->onlyOwnBranch();
        }

        $query->joinWith(['cUser creator', 'rUser responsible'])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(20);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        return $this->render('grid', [
            'dataProvider' => $dataProvider,
        ]);
    }
}