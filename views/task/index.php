<?php

use app\widgets\tasks\TasksGridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-head">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        <?php foreach ($searchModel->displayTypes() as $key => $type) {

                            $class = '';

                            if (isset($searchModel->day) && $key == $searchModel->day) $class = 'active';

                            echo '<li class="' . $class . '"><a class="display-type" data-type="'
                                . $key . '" href="#' . $key . '">' . $type . '</a></li>';

                        } ?>
                    </ul>
                </div>
                <div class="card-body tab-content">
                    <?= TasksGridView::widget([
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                    ]) ?>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
</div>
