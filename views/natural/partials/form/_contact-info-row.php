<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ContactForm */
/* @var $form yii\widgets\ActiveForm */

?>

<div id="info-row-<?= $key ?>" class="row">
    <div class="col-md-3">
        <?php

        $field = $form->field($info, '['.$key.']contact_type_id', [
            'inputOptions' => [
                'class' => 'form-control',
            ],
        ])->dropDownList(
            ArrayHelper::map($contactInfoTypes, 'id', 'name'),
            [
                'class' => 'form-control',
            ]
        )->label(false);

        $field->enableClientValidation = false;

        echo $field;

        ?>
    </div>
    <div class="col-md-5">
        <?php

        $field = $form->field($info, '['.$key.']contact_value');

        $field->enableClientValidation = false;

        echo $field->begin();

        ?>
        <div class="input-group">
            <div class="input-group-content">
                <?= Html::activeTextInput($info, '['.$key.']contact_value', [
                    'maxlength' => true,
                    'class' => 'form-control'])
                ?>
                <div class="form-control-line"></div>
                <?= Html::error($info, '['.$key.']contact_value', ['class' => 'help-block']) ?>
                <?= Html::activeHint($info, '['.$key.']contact_value') ?>
            </div>
            <div class="input-group-btn">
                <?= Html::a('<i class="md md-highlight-remove"></i>', 'javascript:void(0);', [
                    'class' => 'btn btn-flat remove-btn',
                    'data-container-selector' => '#info-row-' . $key ,
                ]) ?>
            </div>
        </div>
        <?= $field->end() ?>
    </div>
</div>