<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ContactsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="leads-grid-widget">
    <div class="row">
        <div class="col-sm-12">
            <?php Pjax::begin([
                'id' => 'pjaxLeadList',
                'enablePushState' => false,
                'clientOptions' => [
                    'method' => 'POST'
                ],
            ]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-hover table-condensed'
                ],
                'rowOptions' => function ($model) {
                    return ['class' => 'as-link model-row',
                        'data-href' => Url::to(['@leads-view', 'id' => $model->id]),
                        'data-name' => $model->getIdentifier(),
                        'data-delete' => Url::to(['@leads-delete', 'id' => $model->id]),
                        'data-update' => Url::to(['@leads-update', 'id' => $model->id]),
                    ];
                },
                'columns' => [
                    [
                        'attribute' => 'product',
                        'format' => 'text',
                        'label' => 'Продукты',
                        'options' => [
                            'class' => 'col-md-3',
                        ],
                        'value' => function ($model) {
                            return $model->getIdentifier();
                        }
                    ],
                    [
                        'attribute' => 'sum',
                        'format' => 'raw',
                        'label' => 'Сумма',
                        'options' => [
                            'class' => 'col-md-1',
                        ],
                        'value' => function ($model) {
                            return '<span class="mask" data-inputmask-alias="integer"
                                        data-inputmask-autogroup="true" data-inputmask-groupseparator=",">' . $model->sum . '</span>';
                        }
                    ],
                    [
                        'attribute' => 'state',
                        'label' => 'Статус',
                        'format' => 'html',
                        'options' => [
                            'class' => 'col-md-2',
                        ],
                        'value' => function ($model) {
                            return '<span class="btn btn-sm" style="background-color: ' . $model->leadStatus->color
                                . '"><span class="status-name">' . $model->leadStatus->name . '</span></span>';
                        },
                    ],
                    [
                        'attribute' => 'branch',
                        'format' => 'text',
                        'label' => 'Отделение',
                        'options' => [
                            'class' => 'col-md-1',
                        ],
                        'value' => function ($model) {
                            return $model->rUser->profile->branch->code;
                        },
                        'visible' => Yii::$app->user->can('manageAllLeads'),
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'label' => 'Создана',
                        'options' => [
                            'class' => 'col-md-1',
                        ],
                    ],
                    [
                        'attribute' => 'responsible',
                        'format' => 'raw',
                        'label' => 'Ответственный',
                        'options' => [
                            'class' => 'col-md-1',
                        ],
                        'value' => function ($model) {
                            return Html::a($model->rUser->profile->name,
                                ['@user-profile', 'id' => $model->rUser->id],
                                ['data-pjax' => '0']
                            );
                        },
                    ],
                    [
                        'attribute' => 'creator',
                        'format' => 'raw',
                        'label' => 'Создал',
                        'options' => [
                            'class' => 'col-md-1',
                        ],
                        'value' => function ($model) {
                            return Html::a($model->cUser->profile->name,
                                ['@user-profile', 'id' => $model->cUser->id],
                                ['data-pjax' => '0']
                            );
                        },
                    ],
                ],
                'summary' => "Показано <b>{begin} - {end}</b> из <b>{totalCount}</b>",
                'emptyText' => 'Подходящих записей не найдено.',
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
