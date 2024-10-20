<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LeadsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сделки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leads-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php Pjax::begin([
                        'id' => 'pjaxList',
                        'options' => [
                            'class' => 'pjax-list',
                        ],
                    ]); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'table table-hover table-condensed'
                        ],
                        'rowOptions' => function ($model) {
                            return ['class' => 'as-link model-row',
                                'data-href' => Url::to(['view', 'id' => $model->id]),
                                'data-name' => $model->getIdentifier(),
                                'data-delete' => Url::to(['delete', 'id' => $model->id]),
                                'data-update' => Url::to(['update', 'id' => $model->id]),
                            ];
                        },
                        'columns' => [
                            [
                                'attribute' => 'contact',
                                'label' => 'Клиент',
                                'format' => 'raw',
                                'options' => [
                                    'class' => 'col-md-2',
                                ],
                                'value' => function ($model) {

                                    $name = '<small>' . $model->contacts->getType() . ':</small>';

                                    $link = Html::a($model->contacts->getIdentifier(),
                                        ['@' . $model->contacts->getTypeLabel() . '-view', 'id' => $model->contacts->id],
                                        ['data-pjax' => '0']);

                                    $html = '<div class="related-list-block">' . $name . $link . '</div>';

                                    return $html;
                                },
                            ],
                            [
                                'attribute' => 'product',
                                'format' => 'text',
                                'label' => 'Продукты',
                                'options' => [
                                    'class' => 'col-md-2',
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
                                    return '<span class="btn btn-sm" style="background-color: '
                                        . $model->leadStatus->color . '"><span class="status-name">'
                                        . $model->leadStatus->name . '</span></span>';
                                }
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
                                'visible' => empty($searchModel->branch) && Yii::$app->user->can('manageAllLeads'),
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
                                'visible' => $searchModel->own != $searchModel::OWN_RESPONSIBLE,
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
                                'visible' => $searchModel->own != $searchModel::OWN_CREATOR,
                            ],
                        ],
                        'summary' => "Показано <b>{begin} - {end}</b> из <b>{totalCount}</b>",
                        'emptyText' => 'Подходящих записей не найдено.',
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
</div>
