<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


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
    <div class="card">
        <div class="card-head style-primary">
            <header><?= Html::encode($this->title) ?></header>
        </div>
        <div class="card-body">
            <?= $model->errorSummary($form) ?>
            <?= $this->render('partials/form/_contact', [
                'form' => $form,
                'model' => $model,
            ]) ?>
            <?= $this->render('partials/form/_contact-infos', [
                'form' => $form,
                'model' => $model,
            ]) ?>
            <?= $this->render('partials/form/_additional-values.php', [
                'form' => $form,
                'model' => $model,
            ]) ?>
        </div><!--end .card-body -->
        <div class="card-actionbar">
            <div class="card-actionbar-row">
                <?= Html::submitButton($model->legal->isNewRecord ? 'Сохранить' : 'Обновить', [
                    'class' => 'btn btn-flat btn-primary ink-reaction btn-loading-state',
                    'data-loading-text' => "<i class='fa fa-spinner fa-spin'></i> Сохранение"
                ]) ?>
            </div>
        </div>
    </div><!--end .card -->
    <?php ActiveForm::end(); ?>
</div>
