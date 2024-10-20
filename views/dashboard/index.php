<?php

use app\widgets\contacts\ContactsDashboardWidget;
use app\widgets\tasks\TasksGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel app\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рабочий стол';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-lg-12">
    <?= $this->render('_leads'); ?>
</div>
<div class="col-lg-12">
    <div class="row">
        <div class="col-md-8">
            <h2 class="text-primary">Мои задачи</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php Pjax::begin([
                'id' => 'pjaxSearch',
                'options' => [
                    'class' => 'pjax-search',
                    'data-reload-container' => '#pjaxList',
                ],
            ]) ?>
            <?php $form = ActiveForm::begin([
                'action' => Url::to(['index']),
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
                ],
            ]); ?>
            <?= Html::activeHiddenInput($searchModel, 'day'); ?>
            <?php ActiveForm::end(); ?>
            <?php Pjax::end(); ?>
            <div class="card">
                <div class="card-head">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        <?php foreach ($searchModel->displayTypes() as $key => $type) {

                            if ($key != $searchModel::ALL) {

                                $class = '';

                                if (isset($searchModel->day) && $key == $searchModel->day) $class = 'active';

                                echo '<li class="' . $class . '"><a class="display-type" data-type="'
                                    . $key . '" href="#' . $key . '">' . $type . '</a></li>';
                            }

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
<div class="col-lg-12">
    <div class="row">
        <div class="col-md-6">
            <h2 class="text-primary">Последние клиенты</h2>
        </div>
        <div class="col-md6" style="text-align: right;margin:13px">
            <a href='<?= Url::to('@legal-create') ?>' class="btn btn-primary">Добавить юр. лицо</a>
            <a href='<?= Url::to('@natural-create') ?>' class="btn btn-primary">Добавить физ. лицо</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?= ContactsDashboardWidget::widget() ?>
        </div>
    </div>
</div>
