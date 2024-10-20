<?php

namespace app\widgets\tasks;


use app\models\TasksSearch;
use kartik\popover\PopoverX;
use Yii;
use yii\base\Widget;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

class TasksGridView extends Widget
{
    public $pjaxConfig = [
        'id' => 'pjaxList',
        'options' => [
            'class' => 'pjax-list',
        ],
    ];

    public $dataProvider;

    /** @var TasksSearch */
    public $searchModel;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run()
    {
        $this->pjaxBegin();
        $this->initGridView();
        $this->pjaxEnd();
    }

    protected function pjaxBegin()
    {
        Pjax::begin($this->pjaxConfig);
    }

    protected function pjaxEnd()
    {
        Pjax::end();
    }

    protected function initGridView()
    {
        $dataProvider = $this->dataProvider;
        $searchModel = $this->searchModel;

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-hover table-condensed'
            ],
            'rowOptions' => function ($model) {

                $class = 'as-link model-row task-container';

                if ($model->isExpired()) {
                    $class .= ' expired';
                }

                return [
                    'id' => 'task'. $model->id,
                    'class' => $class,
                    'data-href' => Url::to(['@tasks-view', 'id' => $model->id]),
                    'data-name' => $model->type->name . ' ' . $model->related->getIdentifier(),
                    'data-delete' => Url::to(['@tasks-delete', 'id' => $model->id]),
                    'data-update' => Url::to(['@tasks-update', 'id' => $model->id]),
                ];
            },
            'columns' => [
                [
                    'attribute' => 'related',
                    'label' => 'Отношение',
                    'format' => 'raw',
                    'options' => [
                        'class' => 'col-md-2',
                    ],
                    'value' => function ($model) {

                        $name = '<small>' . $model->relationType->name . ':</small>';

                        $link = Html::a($model->related->getIdentifier(),
                            ['@' . $model->relationType->alias . '-view', 'id' => $model->related->id],
                            ['data-pjax' => '0']);

                        $html = '<div class="related-list-block">' . $name . $link . '</div>';

                        return $html;
                    },
                    'visible' => empty($searchModel->relid) && empty($searchModel->reltid),
                ],
                [
                    'attribute' => 'type',
                    'label' => 'Тип',
                    'format' => 'raw',
                    'options' => [
                        'class' => 'col-md-1',
                    ],
                    'value' => function ($model) {
                        return '<label class="radio-inline radio-styled ' . $model->type->css
                            . '"><input type="radio" checked><span>' . $model->type->name . '</span></label>';
                    },
                ],
                [
                    'attribute' => 'dateTime',
                    'format' => 'datetime',
                    'label' => 'Дата/Время',
                    'options' => [
                        'class' => 'col-md-1',
                    ],
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'label' => 'Статус',
                    'options' => [
                        'class' => 'col-md-1',
                    ],
                    'value' => function ($model) {
                        return $model->isOpen() ? '<span class="task-status opened">Открыта</span>'
                            : '<span class="task-status closed">Закрыта</span>';
                    },
                    'visible' => !isset($searchModel->status),
                ],
                [
                    'attribute' => 'branch',
                    'format' => 'text',
                    'label' => 'Отделение',
                    'options' => [
                        'class' => 'col-md-1',
                    ],
                    'value' => function ($model) {
                        return $model->user->profile->branch->code;
                    },
                    'visible' => empty($searchModel->branch) && Yii::$app->user->can('manageAllTasks'),
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
                        return Html::a($model->user->profile->name,
                            ['@user-profile', 'id' => $model->user->id],
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
                [
                    'attribute' => 'closed',
                    'format' => 'raw',
                    'label' => 'Закрыл',
                    'contentOptions' => ['class' => 'close-user'],
                    'options' => [
                        'class' => 'col-md-1',
                    ],
                    'value' => function($model) {

                        $return = null;

                        if ($model->status === $model::STATUS_CLOSED) {
                            $return = Html::a($model->closeUser->profile->name,
                                ['@user-profile', 'id' => $model->closeUser->id],
                                ['data-pjax' => '0']
                            );
                        } else {

                            ob_start();
                            PopoverX::begin([
                                'header' => '<i class="md md-comment"></i>',
                                'type' => PopoverX::TYPE_DEFAULT,
                                'placement' => PopoverX::ALIGN_LEFT,
                                'size' => PopoverX::SIZE_LARGE,

                                'toggleButton' => [
                                    'label' => 'Закрыть',
                                    'class'=>'btn btn-flat btn-primary ink-reaction'
                                ],
                                'footer' => false,
                            ]);

                            echo Html::beginForm(Url::to(['@tasks-close', 'id' => $model->id]), 'post', [
                                'class' => 'form close-task-form',
                                'data-task-container-selector' => '#task' . $model->id,
                            ]);

                            echo Html::textarea('close_comment', null, [
                                'class' => 'form-control autosize',
                                'placeholder' => 'Результат',
                                'rows' => 3,
                            ]);

                            echo Html::beginTag('div', ['class' => 'popover-buttons-group  pull-right']);

                            echo Html::submitButton('Закрыть', [
                                'class' => 'btn btn-primary',
                            ]);

                            echo Html::button('Отмена', [
                                'class'=>'btn btn-default',
                                'data-dismiss' => 'popover-x',
                            ]);

                            echo Html::endTag('div');

                            echo Html::endForm();

                            PopoverX::end();
                            $return = ob_get_clean();

                        }

                        return $return;
                    }
                ],
            ],
            'summary' => "Показано <b>{begin} - {end}</b> из <b>{totalCount}</b>",
            'emptyText' => 'Подходящих записей не найдено.',
        ]);
    }
}