<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \app\models\Profile $model
 */

$this->title = 'Настройки профиля';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="section-body contain-lg">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- BEGIN DEFAULT FORM ITEMS -->
                <?= $this->render('_menu', ['model' => $model]) ?>

                <?php $form = ActiveForm::begin([
                    'id' => 'profile-form',
                    'options' => ['class' => 'form'],
                    'fieldConfig' => [
                        'template' => "{input}{label}{error}{hint}",
                    ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                ]); ?>
                <!-- BEGIN FORM TAB PANES -->
                <div class="card-body">
                    <div class="opacity-75 text-primary">
                        <h4>Контактная информация</h4>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'public_email', ['inputOptions' => ['class' => 'form-control']]) ?>
                                </div><!--end .col -->
                                <div class="col-md-12">
                                    <?= $form->field($model, 'contact_phone', ['inputOptions' => [
                                        'class' => 'form-control',
                                        'data-inputmask-alias' => "phone",
                                    ]]) ?>
                                </div><!--end .col -->
                                <div class="col-md-12">
                                    <?= $form->field($model, 'internal_phone', ['inputOptions' => [
                                        'class' => 'form-control',
                                        'data-inputmask' => "'mask':'9999'",
                                    ]]) ?>
                                </div><!--end .col -->
                            </div><!--end .row -->
                        </div><!--end .col -->
                    </div><!--end .row -->
                </div><!--end .card-body.tab-content -->
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
