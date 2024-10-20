<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\ResendForm $model
 */

$this->title = 'Отправка подтверждающего сообщения';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card contain-sm style-transparent">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <br/>
                <span class="text-lg text-bold text-primary"><?= Html::encode($this->title) ?></span>
                <br/><br/>
                <?php $form = ActiveForm::begin([
                    'id' => 'resend-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'options' => [
                        'class' => 'form floating-label',
                    ],
                ]); ?>

                <?= $form->field($model, 'email',
                    [
                        'template' => '{input}{label}{error}{hint}',
                        'inputOptions' => ['class' => 'form-control', 'tabindex' => '1']
                    ]
                );
                ?>

                <br>
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-6 text-right">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-block btn-primary btn-raised', 'tabindex' => '2']) ?><br>
                    </div><!--end .col -->
                </div><!--end .row -->

                <?php ActiveForm::end(); ?>
            </div><!--end .col -->
            <div class="col-sm-5 col-sm-offset-1 text-center">
                <br>
                <br>
                <h3 class="text-light">
                    Хотите войти ?
                </h3>
                <?= Html::a('Авторизация', ['/user/security/login'], ['class' => 'btn btn-block btn-raised btn-primary']) ?>
            </div><!--end .col -->
        </div><!--end .row -->
    </div><!--end .card-body -->
</div><!--end .card -->