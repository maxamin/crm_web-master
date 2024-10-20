<?php

use app\models\Branch;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 * @var dektrium\user\models\Profile $profile
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
                    <?= $form->field($profile, 'name') ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($profile, 'branch_id')->dropDownList(
                        ArrayHelper::map(Branch::find()->orderBy('name')->select(['id', 'name'])->all(), 'id', 'name'),
                        [
                            'class' => 'form-control select2-list',
                            'tabindex' => '5',
                        ]
                    ) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($profile, 'public_email') ?>
                </div><!--end .col -->
                <div class="col-md-12">
                    <?= $form->field($profile, 'contact_phone', ['inputOptions' => [
                        'data-inputmask-alias' => "phone",
                    ]]) ?>
                </div><!--end .col -->
                <div class="col-md-12">
                    <?= $form->field($profile, 'internal_phone', ['inputOptions' => [
                        'data-inputmask' => "'mask':'9999'",
                    ]]) ?>
                </div><!--end .col -->
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
