<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use app\models\Users;
use app\models\TasksTypes;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row">
    <div class="col-md-12">
        <div class="widget-add-btn-container">
            <?= Html::a('Добавить задачу', 'javascript:void(0);', [
                'class' => 'btn btn-primary pull-right show-btn',
                'data-container-selector' => '#tasksCreateWidgetFormContainer',
            ]) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php Pjax::begin([
            'id' => 'pjaxTaskCreate',
            'options' => [
                'class' => 'widget-pjax-create',
                'data-reload-container' => '#pjaxTaskList',
            ],
        ]); ?>
        <div id="tasksCreateWidgetFormContainer" class="widget-form-container" style="<?php if ($hide): ?>display: none;<?php endif; ?>">
            <div class="card">
                <div class="card-head style-primary">
                    <header>Добавление задачи</header>
                    <div class="tools">
                        <a class="btn btn-icon-toggle hide-btn" data-container-selector="#tasksCreateWidgetFormContainer"><i class="md md-close"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tasks-form">

                        <?php $form = ActiveForm::begin([
                            'options' => [
                                'class' => 'form floating-label',
                                'autocomplete' => 'off',
                                'data-pjax' => true,
                            ],
                        ]); ?>

                        <?= $form->errorSummary($model, [
                            'header' => '<p><i class="fa fa-fw fa-exclamation-triangle"></i>При попытке сохранения были обнаружены следующие ошибки:</p>',
                        ]) ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group no-padding">
                                    <label class="control-label">Тип задачи</label>
                                    <?php

                                    $taskTypes = TasksTypes::find()->all();
                                    $types = ArrayHelper::index($taskTypes, ['id']);

                                    echo $form->field($model, 'task_type_id')->radioList(ArrayHelper::map($taskTypes, 'id', 'name'), [
                                        'item' => function ($index, $label, $name, $checked, $value) use ($types) {

                                            $checked = $checked ? 'checked' : '';

                                            $return = '<label class="radio-inline radio-styled ' . $types[$value]->css . ' ">';
                                            $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $checked . '>';
                                            $return .= '<span>' . $label . '</span>';
                                            $return .= '</label>';

                                            return $return;
                                        }
                                    ])->label(false) ?>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'day', [
                                    'template' => '{input}{label}{error}{hint}',
                                ])->widget(DatePicker::className(), [
                                    'dateFormat' => 'dd.MM.yyyy',
                                    'options' => [
                                        'class' => 'form-control',
                                        'data-inputmask-alias' => 'dd.mm.yyyy',
                                        'data-inputmask-placeholder' => 'дд.мм.гггг',
                                    ],
                                    'clientOptions' => [
                                        'format' => 'dd.mm.yyyy',
                                        'autoclose' => true,
                                        'todayHighlight' => true,
                                        'language' => 'ru',
                                    ],
                                ])->label('<i class="fa fa-calendar"></i> Дата') ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'hour', [
                                    'template' => '{input}{label}{error}{hint}',
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                        'data-inputmask-alias' => 'hh:mm',
                                        'data-inputmask-placeholder' => 'чч:мм',
                                    ],
                                ])->textInput()->label('<i class="fa fa-clock-o"></i> Время') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'user_id', [
                                    'template' => '{input}{label}{error}{hint}',
                                ])->dropDownList(
                                    ArrayHelper::map(Users::find()->joinWith(['profile'])->orderBy('profile.name')
                                        ->select(['id', 'profile.name'])->all(), 'id', 'profile.name'),
                                    ['class' => 'form-control select2-list']
                                )->label('Ответственный') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?= $form->field($model, 'comment', ['template' => '{input}{label}{error}{hint}'])
                                    ->textarea(['class' => 'form-control', 'rows' => 3])->label('Описание') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?= $form->field($model, 'relation_id', ['template' => '{input}'])->hiddenInput() ?>
                                <?= $form->field($model, 'relation_type', ['template' => '{input}'])->hiddenInput() ?>
                                <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', [
                                    'class' => 'pull-right btn btn-flat btn-primary ink-reaction btn-loading-state',
                                    'data-loading-text' => "<i class='fa fa-spinner fa-spin'></i> Сохранение"
                                ]) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>