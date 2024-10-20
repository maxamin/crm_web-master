<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $model
 */

$this->title = 'Настройки аккаунта';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="section-body contain-lg">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- BEGIN DEFAULT FORM ITEMS -->
                <?= $this->render('_menu', ['model' => $model]) ?>

                <?php $form = ActiveForm::begin([
                    'id' => 'account-form',
                    'options' => ['class' => 'form'],
                    'fieldConfig' => [
                        'template' => "{input}{label}{error}{hint}",
                        'inputOptions' => [
                            'class' => 'form-control',
                        ]
                    ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]); ?>
                <!-- BEGIN FORM TAB PANES -->
                <div class="card-body">
                    <div class="opacity-75 text-primary">
                        <h4>Смена пароля</h4>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'new_password')->passwordInput() ?>
                                </div><!--end .col -->
                                <div class="col-md-12">
                                    <?= $form->field($model, 'current_password')->passwordInput() ?>
                                </div><!--end .col -->
                            </div><!--end .row -->
                        </div><!--end .col -->
                    </div><!--end .row -->
                </div><!--end .card-body -->
                <!-- END FORM TAB PANES -->

                <!-- BEGIN FORM FOOTER -->
                <div class="card-actionbar">
                    <div class="card-actionbar-row">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-flat btn-primary']) ?>
                    </div><!--end .card-actionbar-row -->
                </div><!--end .card-actionbar -->
                <!-- END FORM FOOTER -->
                <?php ActiveForm::end(); ?>
            </div><!--end .card -->
        </div><!--end .col -->
        <!-- END ADD CONTACTS FORM -->
    </div>
</div>
