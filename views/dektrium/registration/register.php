<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\models\Branch;
use app\models\Cities;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $model
 * @var dektrium\user\Module $module
 */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card contain-sm style-transparent">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <br/>
                <span class="text-lg text-bold text-primary"><?= mb_strtoupper(Html::encode($this->title)) ?></span>
                <br/><br/>
                <?php $form = ActiveForm::begin([
                    'id' => 'registration-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'options' => [
                        'class' => 'form floating-label',
                    ],
                ]); ?>

                <?= $form->field($model, 'email',
                    [
                        'template' => '{input}{label}{error}{hint}',
                        'inputOptions' => ['class' => 'form-control', 'tabindex' => '1']
                    ]) ?>

                <?= $form->field($model, 'username', [
                    'template' => '{input}{label}{error}{hint}',
                    'inputOptions' => ['class' => 'form-control', 'tabindex' => '2']
                ]) ?>

                <?php if ($module->enableGeneratingPassword == false): ?>
                    <?= $form->field($model, 'password', [
                        'template' => '{input}{label}{error}{hint}',
                        'inputOptions' => ['class' => 'form-control', 'tabindex' => '3']
                    ])->passwordInput() ?>
                <?php endif ?>

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

                <br>
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-4 text-right">
                        <?= Html::submitButton(
                            'Зарегистрироваться',
                            ['class' => 'btn btn-primary btn-block btn-raised', 'tabindex' => '6']
                        ) ?>
                    </div><!--end .col -->
                </div><!--end .row -->

                <?php ActiveForm::end(); ?>
            </div><!--end .col -->
            <div class="col-sm-5 col-sm-offset-1 text-center">
                <br><br>
                <?php if ($module->enableRegistration): ?>
                    <h3 class="text-light">
                        Уже зарегистрированы ?
                    </h3>
                    <?= Html::a('Авторизация', ['/user/security/login'], ['class' => 'btn btn-block btn-raised btn-primary']) ?>
                <?php endif ?>
            </div><!--end .col -->
        </div><!--end .row -->
    </div><!--end .card-body -->
</div><!--end .card -->
