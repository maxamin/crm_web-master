<?php

use app\widgets\tasks\TasksGridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="tasks-grid-widget">
    <div class="row">
        <div class="col-sm-12">
            <?= TasksGridView::widget([
                'pjaxConfig' => [
                    'id' => 'pjaxTaskList',
                    'enablePushState' => false,
                    'clientOptions' => [
                        'method' => 'POST'
                    ],
                ],
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]) ?>
        </div>
    </div>
</div>
