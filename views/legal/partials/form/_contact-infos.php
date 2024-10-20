<?php

use yii\helpers\Html;

use app\models\ContactsInfoTypes;
use app\models\ContactsInfos;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ContactForm */
/* @var $form yii\widgets\ActiveForm */

$contactsInfos = new ContactsInfos(); //for empty template
$contactsInfos->loadDefaultValues();

$contactInfoTypes = ContactsInfoTypes::find()->orderBy('ord')->all(); //for dropDownList

//default input
if (!Yii::$app->request->post() && $model->legal->isNewRecord) {
    $contactsInfosDefaults = [];

    foreach (array_slice($contactInfoTypes, 0, 1) as $contactInfoType) {
        $newInfo = new ContactsInfos();
        $newInfo->loadDefaultValues();
        $newInfo->contact_type_id = $contactInfoType->id;
        $contactsInfosDefaults[] = $newInfo;
    }

    $model->contactsInfos = $contactsInfosDefaults;
}

?>

<div id="contactsInfos">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <em>Контактная информация</em>
            </div>
        </div>
    </div>
    <div id="contactsInfosContainer">
        <?php foreach ($model->contactsInfos as $key => $info): ?>
            <?php
            if ($info->isNewRecord) {
                $key = strpos($key, 'new') !== false ? $key : 'new' . $key;
            } else {
                $key = $info->id;
            }
            ?>
            <?= $this->render('_contact-info-row', [
                'form' => $form,
                'info' => $info,
                'key' => $key,
                'contactInfoTypes' => $contactInfoTypes,
            ]); ?>
        <?php endforeach; ?>

        <!-- hidden template for add button -->
        <div id="contactsInfosTemplate" hidden="hidden">
            <?= $this->render('_contact-info-row', [
                'form' => $form,
                'info' => $contactsInfos,
                'key' => 'temp_key',
                'contactInfoTypes' => $contactInfoTypes,
            ]); ?>
        </div>
        <span id="contactsInfoKey" data-key="<?= isset($key) ? str_replace('new', '', $key) : 0 ?>" hidden="hidden"></span>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= Html::a('<i class="md md-add-circle"></i> Добавить', 'javascript:void(0);', [
                'id' => 'newContactsInfos',
                'class' => 'btn ink-reaction btn-primary-bright',
            ]) ?>
        </div>
    </div>
</div><!--end contactsInfos -->