<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ContactForm */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="contacts-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form floating-label',
            'autocomplete' => 'off',
        ],
    ]); ?>
    <div class="form-wizard-nav" style="padding-bottom: 50px;">
        <div class="progress" style="width: 75%;">
            <div class="progress-bar progress-bar-primary" style="width: 0%;"></div>
        </div>
        <ul class="nav nav-justified nav-pills">
            <li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true"><span class="step">1</span> <span
                            class="title">Общая информация</span></a></li>
            <li class=""><a href="#tab2" data-toggle="tab" aria-expanded="false"><span class="step">2</span> <span
                            class="title">Контактные данные</span></a></li>
            <li class=""><a href="#tab3" data-toggle="tab" aria-expanded="false"><span class="step">3</span> <span
                            class="title">Юридическое лицо</span></a></li>
            <li class=""><a href="#tab4" data-toggle="tab" aria-expanded="false"><span class="step">4</span> <span
                            class="title">Дополнительные параметры</span></a></li>
        </ul>
    </div><!--end .form-wizard-nav -->
    <?= $model->errorSummary($form) ?>

    <div class="tab-content clearfix">
        <?= $this->render('partials/form/_tab1', [
            'form' => $form,
            'model' => $model,
        ]) ?>
        <?= $this->render('partials/form/_tab2', [
            'form' => $form,
            'model' => $model,
        ]) ?>
        <?= $this->render('partials/form/_tab3', [
            'form' => $form,
            'model' => $model,
        ]) ?>
        <?= $this->render('partials/form/_tab4', [
            'form' => $form,
            'model' => $model,
        ]) ?>
        <ul class="pager wizard">
            <li class="previous disabled"><a class="btn-raised" href="javascript:void(0);">Назад</a></li>
            <li class="next"><a class="btn-raised" href="javascript:void(0);">Далее</a></li>
        </ul>
        <?php ActiveForm::end(); ?>
    </div>
</div>
