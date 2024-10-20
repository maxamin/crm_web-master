<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="contacts-dashboard-widget">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-hover table-condensed'
        ],
        'rowOptions' => function ($model) {
            return ['class' => 'as-link model-row',
                'data-href' => Url::to(['@' . $model->getTypeLabel() . '-view', 'id' => $model->id]),
                'data-name' => $model->name,
                'data-delete' => Url::to(['@' . $model->getTypeLabel() . '-delete', 'id' => $model->id]),
                'data-update' => Url::to(['@' . $model->getTypeLabel() . '-update', 'id' => $model->id]),
            ];
        },
        'columns' => [
            [
                'attribute' => 'type',
                'format' => 'raw',
                'label' => 'Клиент',
                'options' => [
                    'class' => 'col-md-2',
                ],
                'value' => function ($model) {

                    $name = '<small>' . $model->getType() . ':</small>';

                    $link = Html::a($model->name,
                        ['@' . $model->getTypeLabel() . '-view', 'id' => $model->id],
                        ['data-pjax' => '0']);

                    $html = '<div class="related-list-block">' . $name . $link . '</div>';

                    return $html;
                },

            ],
            [
                'attribute' => 'okpo',
                'format' => 'text',
                'label' => 'ОКПО/ИНН',
                'options' => [
                    'class' => 'col-md-1',
                ],
            ],
            [
                'attribute' => 'active',
                'format' => 'html',
                'label' => 'Активен',
                'options' => [
                    'class' => 'col-md-1',
                ],
                'value' => function ($model) {
                    return $model->active ? '<span class="opened">Да</span>' : '<span class="closed">Нет</span>';
                }
            ],
            [
                'attribute' => 'contactsInfos',
                'label' => 'Контакты',
                'format' => 'html',
                'options' => [
                    'class' => 'col-md-2',
                ],
                'value' => function ($model) {
                    $htmlResult = '';
                    foreach ($model->contactsInfos as $key => $value) {
                        $htmlResult .= '<b>' . $value->contactType->name . '</b>: ' . $value->contact_value . '<br>';
                    }
                    return $htmlResult !== '' ? $htmlResult : null;
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
                'visible' => Yii::$app->user->can('manageAllContacts'),
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
                'attribute' => 'rUser.profile.name',
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
                }
            ],
            [
                'attribute' => 'cUser.profile.name',
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
                }
            ],
        ],
        'summary' => "Показано <b>{begin} - {end}</b> из <b>{totalCount}</b>",
        'emptyText' => 'Подходящих записей не найдено.',
    ]); ?>
</div>
