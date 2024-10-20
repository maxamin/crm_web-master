<?php

use app\models\Branch;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $model
 */

$this->title = 'Создание нового аккаунта';
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <?= Alert::widget([
            'options' => ['class' => 'alert alert-dismissible alert-callout alert-info'],
            'body' => Yii::t('user', 'Credentials will be sent to the user by email') .
                '.&nbsp' . Yii::t('user', 'A password will be generated automatically if not provided') . '.',
            'closeButton' => [
                'label' => '<i aria-hidden="true" class="md md-highlight-remove"></i>',
            ]
        ]) ?>
    </div>
    <div class="col-md-6 col-md-offset-3">
        <div class="card">
            <div class="card-head style-primary">
                <header><?= $this->title ?></header>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin([
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'options' => [
                        'class' => 'form floating-label',
                    ],
                ]); ?>

                <?= $form->field($model, 'email', [
                        'template' => '{input}{label}{error}{hint}',
                        'inputOptions' => ['class' => 'form-control', 'tabindex' => '1']
                    ]) ?>

                <?= $form->field($model, 'username', [
                    'template' => '{input}{label}{error}{hint}',
                    'inputOptions' => ['class' => 'form-control', 'tabindex' => '2']
                ]) ?>

                <?= $form->field($model, 'password', [
                    'template' => '{input}{label}{error}{hint}',
                    'inputOptions' => ['class' => 'form-control', 'tabindex' => '3']
                ])->passwordInput() ?>

                <?= $form->field($model, 'name', [
                    'template' => '{input}{label}{error}{hint}',
                    'inputOptions' => ['class' => 'form-control', 'tabindex' => '4']
                ]) ?>

                <?= $form->field($model, 'branch_id', [
                    'template' => '{input}{label}{error}{hint}',
                ])->dropDownList(
                    ArrayHelper::map(Branch::find()->orderBy('name')->select(['id', 'name'])->all(), 'id', 'name'),
                    [
                        'class' => 'form-control select2-list',
                        'tabindex' => '5',
                    ]
                ) ?>

                <div class="card-actionbar">
                    <div class="card-actionbar-row">
                        <?= Html::submitButton(
                            'Создать',
                            ['class' => 'btn btn-primary btn-raised', 'tabindex' => '6']
                        ) ?>
                    </div><!--end .card-actionbar-row -->
                </div><!--end .card-actionbar -->

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
