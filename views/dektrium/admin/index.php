<?php

use app\models\AuthItem;
use app\models\Branch;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;


/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \dektrium\user\models\UserSearch $searchModel
 */

$this->title = 'Управление пользователями';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="contacts-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-2 col-lg-offset-10 col-sm-4 col-sm-offset-8">
            <div class="card">
                <div class="card-body small-padding">
                    <?= Html::a('<i class="fa fa-plus"></i> Добавить',
                        ['create'], ['class' => 'btn btn-primary']
                    ) ?>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php Pjax::begin([
                        'id' => 'pjaxList',
                        'options' => [
                            'class' => 'pjax-list',
                        ],
                    ]) ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'rowOptions' => function ($model) {
                            return ['class' => 'as-link model-row',
                                'data-href' => Url::to(['info', 'id' => $model->id]),
                                'data-name' => $model->username,
                                'data-delete' => Url::to(['delete', 'id' => $model->id]),
                                'data-update' => Url::to(['update', 'id' => $model->id]),
                            ];
                        },
                        'tableOptions' => [
                            'class' => 'table table-hover table-condensed'
                        ],
                        'summary' => "Показано <b>{begin} - {end}</b> из <b>{totalCount}</b>",
                        'emptyText' => 'Подходящих записей не найдено.',
                        'columns' => [
                            [
                                'attribute' => 'id',
                                'label' => '#',
                            ],
                            [
                                'attribute' => 'username',
                                'options' => [
                                    'class' => 'col-md-1',
                                ],
                            ],
                            [
                                'attribute' => 'email',
                                'options' => [
                                    'class' => 'col-md-1',
                                ],
                            ],
                            [
                                'attribute' => 'name',
                                'label' => 'ФИО',
                                'options' => [
                                    'class' => 'col-md-2',
                                ],
                                'value' => function ($model) {
                                    return $model->profile->name;
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'datetime',
                                'options' => [
                                    'class' => 'col-md-1',
                                ],
                            ],
                            [
                                'label' => 'Последняя авторизация',
                                'attribute' => 'last_login_at',
                                'value' => function ($model) {
                                    if (!$model->last_login_at || $model->last_login_at == 0) {
                                        return 'Никогда';
                                    } else {
                                        return Yii::$app->formatter->asDatetime($model->last_login_at);
                                    }
                                },
                                'options' => [
                                    'class' => 'col-md-1',
                                ],
                            ],
                            [
                                'attribute' => 'branch',
                                'label' => 'Отделение',
                                'format' => 'text',
                                'options' => [
                                    'class' => 'col-md-1',
                                ],
                                'value' => function ($model) {
                                    return $model->profile->branch->code;
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'branch',
                                    ArrayHelper::map(Branch::find()->all(), 'code', 'code'),
                                    [
                                        'class' => 'form-control select2-list',
                                        'data-placeholder' => '...',
                                        'prompt' => '',
                                    ]),
                            ],
                            [
                                'label' => 'Роли',
                                'attribute' => 'role',
                                'format' => 'raw',
                                'value' => function ($model) {

                                    $return = [];

                                    foreach (Yii::$app->authManager->getRolesByUser($model->id) as $role) {
                                        $return[] = $role->name;
                                    }

                                    return implode(', ', $return);
                                },
                                'options' => [
                                    'class' => 'col-md-1',
                                ],
                                'filter' => Html::activeDropDownList($searchModel, 'role',
                                    ArrayHelper::map(AuthItem::find()->onlyRoles()->all(), 'name', 'name'),
                                    [
                                        'class' => 'form-control select2-list',
                                        'data-placeholder' => '...',
                                        'prompt' => '',
                                    ]),

                            ],
                            [
                                'label' => 'Активация',
                                'value' => function ($model) {
                                    if ($model->isConfirmed) {
                                        return '<i class="fa fa-check-square-o text-success"></i>';
                                    } else {
                                        return Html::a('<i class="fa fa-check text-success"></i> Активировать',
                                            ['confirm', 'id' => $model->id], [
                                                'class' => 'btn btn-xs btn-default',
                                                'data-method' => 'post',
                                                'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                                            ]);
                                    }
                                },
                                'format' => 'raw',
                                'visible' => Yii::$app->getModule('user')->enableConfirmation,
                            ],
                            [
                                'label' => 'Подтверждение',
                                'value' => function ($model) {
                                    if (!$model->isVerified) {
                                        return Html::a('<i class="fa fa-check text-success"></i> Подтвердить',
                                            ['verify', 'id' => $model->id], [
                                                'class' => 'btn btn-xs btn-default',
                                                'data-method' => 'post',
                                                'data-confirm' => 'Вы уверены, что хотите подтвердить данного пользователя?',
                                            ]);
                                    } else {
                                        return '<i class="fa fa-check-square-o text-success"></i>';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => 'Блокировка',
                                'value' => function ($model) {
                                    if ($model->isBlocked) {
                                        return Html::a('<i class="fa fa-unlock text-success"></i> Отмена',
                                            ['block', 'id' => $model->id],
                                            [
                                                'class' => 'btn btn-xs btn-default',
                                                'data-method' => 'post',
                                                'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                                            ]);
                                    } else {
                                        return Html::a('<i class="fa fa-lock text-danger"></i> Блокировать',
                                            ['block', 'id' => $model->id], [
                                                'class' => 'btn btn-xs btn-default',
                                                'data-method' => 'post',
                                                'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                                            ]);
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{switch} {resend_password}',
                                'buttons' => [
                                    'resend_password' => function ($url, $model, $key) {
                                        if (!$model->isAdmin) {
                                            return Html::a('<i class="fa fa-warning"></i>',
                                                ['resend-password', 'id' => $model->id],
                                                [
                                                    'class' => 'btn btn-xs',
                                                    'title' => 'Сгенерировать новый пароль',
                                                    'data-method' => 'POST',
                                                    'data-confirm' => 'Вы уверены что хотите сгенерировать новый пароль для этого пользователя?',

                                                ]);
                                        }
                                    },
                                    'switch' => function ($url, $model) {
                                        if ($model->id != Yii::$app->user->id && Yii::$app->getModule('user')->enableImpersonateUser) {
                                            return Html::a('<i class="fa fa-external-link-square"></i>',
                                                ['/user/admin/switch', 'id' => $model->id],
                                                [
                                                    'class' => 'btn btn-xs',
                                                    'title' => 'Войти под этим пользователем',
                                                    'data-confirm' => 'Вы уверены, что хотите войти под этим пользователем? Текущая сессия будет утеряна.',
                                                    'data-method' => 'POST',
                                                ]);
                                        }
                                    }
                                ],
                                'contentOptions' => [
                                    'class' => 'text-right'
                                ],
                            ],
                        ],
                    ]); ?>

                    <?php Pjax::end() ?>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
</div>
