<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 */
?>

<?php $this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]) ?>

<?php $form = ActiveForm::begin([
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
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($user, 'username')->textInput(['maxlength' => 255]) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($user, 'password')->passwordInput() ?>
                </div>
            </div><!--end .row -->
        </div><!--end .col -->
    </div><!--end .row -->
</div><!--end .card-body -->
<!-- END FORM TAB PANES -->

<!-- BEGIN FORM FOOTER -->
<div class="card-actionbar">
    <div class="card-actionbar-row">
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-flat btn-primary']) ?>
    </div><!--end .card-actionbar-row -->
</div><!--end .card-actionbar -->
<!-- END FORM FOOTER -->
<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
