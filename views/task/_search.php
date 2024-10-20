<?php

use app\models\Branch;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\TasksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tasks-search">
    <div class="row">
        <?php Pjax::begin([
            'id' => 'pjaxSearch',
            'options' => [
                'class' => 'pjax-search',
                'data-reload-container' => '#pjaxList',
            ],
        ]); ?>
        <?php $form = ActiveForm::begin([
            'action' => Url::to(['index']),
            'method' => 'get',
            'options' => [
                'data-pjax' => 1,
            ],
        ]); ?>

        <?php if (Yii::$app->user->can('manageAllTasks')): ?>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body small-padding">
                        <?= Html::activeDropDownList($model, 'branch',
                            ArrayHelper::map(Branch::find()->all(), 'code', 'fullName'),
                            [
                                'class' => 'form-control select2-list',
                                'data-placeholder' => 'Отделение',
                                'data-allow-clear' => 1,
                                'prompt' => '',
                            ]
                        ); ?>
                    </div><!--end .card-body -->
                </div><!--end .card -->
            </div>
            <div class="clearfix"></div>
        <?php endif; ?>

        <div class="col-lg-6 col-sm-12">
            <div class="card">
                <div class="card-body small-padding">
                    <div class="btn-group radio-uncheckable" data-toggle="buttons">
                        <?php foreach ($model->ownLabels() as $own => $label) {
                            $class = 'btn btn-primary';

                            if (isset($model->own) && $own == $model->own) $class .= ' active';

                            echo Html::activeRadio($model, 'own', [
                                'label' => $label,
                                'uncheck' => null,
                                'value' => $own,
                                'labelOptions' => [
                                    'class' => $class,
                                ],
                            ]);
                        } ?>
                    </div>
                    <div class="btn-group radio-uncheckable" data-toggle="buttons">
                        <?php foreach ($model->statusLabels() as $status => $label) {
                            $class = 'btn btn-primary';

                            if (isset($model->status) && $status == $model->status) $class .= ' active';

                            echo Html::activeRadio($model, 'status', [
                                'label' => $label,
                                'uncheck' => null,
                                'value' => $status,
                                'labelOptions' => [
                                    'class' => $class,
                                ],
                            ]);
                        } ?>
                    </div>
                    <?= Html::activeHiddenInput($model, 'day'); ?>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
        <div class="col-lg-4 col-sm-8">
            <div class="card">
                <div class="card-body small-padding">
                    <?= Html::activeTextInput($model, 'q', ['class' => 'form-control',
                        'autocomplete' => 'off', 'placeholder' => 'Поиск']); ?>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
        <?php ActiveForm::end(); ?>
        <?php Pjax::end(); ?>

        <div class="col-lg-2 col-sm-4">
            <div class="card">
                <div class="card-body small-padding">
                    <?= Html::a('<i class="fa fa-plus"></i> Добавить',
                        ['create'], ['class' => 'btn btn-primary']
                    ) ?>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
</div>
