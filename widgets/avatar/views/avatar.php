<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $profile \app\models\Profile */
/* @var $content string */
/* @var $action string */

?>

<div class="avatar-widget">
    <a class='widget-avatar-container' href='javascript:void(0);' data-toggle='modal' data-target='#uploadAvatar'>
        <?= Html::img($profile->getAvatarUrl(), [
            'class' => 'img-circle border-white border-xl img-responsive auto-width',
            'alt' => $profile->user->username,
        ]); ?>
        <span class="case"><i class="fa fa-plus"></i></span>
    </a>

    <div class="modal fade bs-example-modal-lg" id="uploadAvatar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <?php $form = ActiveForm::begin(['action' => $action, 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="modal-body">
                <div class="jcrop-preview-container">
                    <img class="jcrop-preview" src="">
                </div>

                <?= $form->field($profile, 'x1')->hiddenInput()->label(false); ?>
                <?= $form->field($profile, 'x2')->hiddenInput()->label(false); ?>
                <?= $form->field($profile, 'y1')->hiddenInput()->label(false); ?>
                <?= $form->field($profile, 'y2')->hiddenInput()->label(false); ?>
            </div>
            <div class="modal-footer">
                <label class="btn btn-default avatar-upload-btn pull-left"> Загрузить
                    <?= Html::activeFileInput($profile, 'avatarFile', [
                        'style' => 'display: none',
                        'class' => 'avatar-file',
                        'accept' => 'image/png, image/jpeg',
                    ]) ?>
                </label>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
