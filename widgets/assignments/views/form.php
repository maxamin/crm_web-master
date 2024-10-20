<?php


use app\models\AuthItem;
use dektrium\rbac\models\Assignment;
use kartik\select2\Select2;
use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $model Assignment
 */

?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'enableAjaxValidation'   => false,
    'options' => [
        'class' => 'form',
    ],
]) ?>

<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($model->updated): ?>

                        <?= Alert::widget([
                            'options' => [
                                'class' => 'alert alert-dismissible alert-callout alert-success'
                            ],
                            'closeButton' => [
                                'label' => '<i aria-hidden="true" class="md md-highlight-remove"></i>',
                            ],
                            'body' => 'Роли обновлены.',
                        ]) ?>

                    <?php endif ?>

                    <?= Html::activeHiddenInput($model, 'user_id') ?>

                    <?= $form->field($model, 'items')->dropDownList(
                        ArrayHelper::map(AuthItem::find()->onlyRoles()->all(), 'name', 'fullName'),
                        [
                            'multiple'=>'multiple',
                            'class' => 'form-control select2-list',
                            'data-placeholder' => 'Выберете роли ...',
                            'data-allow-clear' => 1,
                            'prompt' => '',
                        ]
                    )->label('Роли') ?>
                </div>
            </div><!--end .row -->
        </div><!--end .col -->
    </div><!--end .row -->
</div><!--end .card-body -->

<div class="card-actionbar">
    <div class="card-actionbar-row">
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-flat btn-primary']) ?>
    </div><!--end .card-actionbar-row -->
</div><!--end .card-actionbar -->

<?php ActiveForm::end() ?>

