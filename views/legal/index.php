<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LegalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Юридические лица';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="contacts-index">
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
                                'data-name' => $model->name,
                                'data-delete' => Url::to(['delete', 'id' => $model->id]),
                                'data-update' => Url::to(['update', 'id' => $model->id]),
                            ];
                        },
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'format' => 'text',
                                'label' => 'Название',
                                'options' => [
                                    'class' => 'col-md-3',
                                ],
                            ],
                            [
                                'attribute' => 'okpo',
                                'format' => 'text',
                                'label' => 'ОКПО',
                                'options' => [
                                    'class' => 'col-md-1',
                                ],
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'html',
                                'label' => 'Активен',
                                'options' => [
                                    'class' => 'col-md-1',
                                ],
                                'value' => function ($model) {
                                    return $model->active ? '<span class="opened">Да</span>'
                                        : '<span class="closed">Нет</span>';
                                },
                                'visible' => !isset($searchModel->status),
                            ],
                            [
                                'attribute' => 'contactsLinks',
                                'label' => 'Физ.лиц',
                                'format' => 'text',
                                'options' => [
                                    'class' => 'col-md-1',
                                ],
                                'value' => function ($model) {
                                    return count($model->contactsLinks);
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
                                'visible' => !(isset($searchModel->branch) && $searchModel->branch !== ''),

                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'datetime',
                                'label' => 'Создан',
                                'options' => [
                                    'class' => 'col-md-1',
                                ],
                            ],
                            [
                                'attribute' => 'responsible',
                                'format' => 'raw',
                                'label' => 'Ответственный',
                                'options' => [
                                    'class' => 'col-md-2',
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
                                    'class' => 'col-md-2',
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
