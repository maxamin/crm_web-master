<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use app\models\AdditionFields;
use app\models\AdditionFieldsValues;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ContactForm */
/* @var $form yii\widgets\ActiveForm */

$additionFields = AdditionFields::find()->with('additionalValuesDirectories')
    ->andWhere('active=1')->andWhere('type=' . $model->legal->type)->all();

$additionFieldsValues = $model->additionFieldsValues;
$additionFieldsValuesDefaults = [];

foreach ($additionFields as $key => $field) { //form fields

    $additionValue = null;

    foreach ($additionFieldsValues as $fieldValue) {
        if ($fieldValue->addition_field_id == $field->id) { // if model has form field
            $additionValue = $fieldValue;
        }
    }

    if (!$additionValue) {
        $additionValue = new AdditionFieldsValues();
        $additionValue->addition_field_id = $field->id;
    }

    $additionFieldsValuesDefaults[] = $additionValue;
}

$model->additionFieldsValues = $additionFieldsValuesDefaults;

?>

<div id="additionalFields">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <em>Дополнительные параметры</em>
            </div>
        </div>
    </div>
    <div class="row">
        <?php foreach ($model->additionFieldsValues as $key => $fieldValue):

            $fieldValue->setScenario(AdditionFieldsValues::SCENARIO_LEGAL);

            if ($fieldValue->isNewRecord) {
                $key = strpos($key, 'new') !== false ? $key : 'new' . $key;
            } else {
                $key = $fieldValue->id;
            }
            ?>
            <?php if ($fieldValue->additionField->is_directory): ?>
                <div class="col-sm-6">
                    <?= $form->field($fieldValue, '['.$key.']value', [
                        'template' => '{input}{label}{error}{hint}',
                        'inputOptions' => ['class' => 'form-control'],
                    ])->dropDownList(
                        ArrayHelper::map($fieldValue->additionField->additionalValuesDirectories, 'value', 'name'),
                        ['class' => 'form-control']
                    )->label($fieldValue->additionField->name) ?>
                </div>
            <?php else: ?>
                <div class="col-sm-6">
                    <?= $form->field($fieldValue, '['.$key.']value', [
                        'template' => '{input}{label}{error}{hint}',
                        'inputOptions' => ['class' => 'form-control'],
                    ])->textInput(['maxlength' => true])->label($fieldValue->additionField->name) ?>
                </div>
            <?php endif; ?>
            <?= Html::activeHiddenInput($fieldValue, '['.$key.']addition_field_id') ?>
        <?php endforeach; ?>
    </div>
</div><!--end additionalFields -->