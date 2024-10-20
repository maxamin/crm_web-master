<?php


use dektrium\user\widgets\Connect;
use dektrium\user\models\LoginForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card contain-sm style-transparent">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <br/>
                <span class="text-lg text-bold text-primary"><?= mb_strtoupper(Html::encode($this->title)) ?></span>
                <br/><br/>
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                    'validateOnType' => false,
                    'validateOnChange' => false,
                    'options' => [
                        'class' => 'form floating-label',
                    ],
                ]) ?>

                <?php if ($module->debug): ?>
                    <?= $form->field($model, 'login', [
                        'inputOptions' => [
                            'class' => 'form-control',
                            'tabindex' => '1']])->dropDownList(LoginForm::loginList());
                    ?>

                <?php else: ?>

                    <?= $form->field($model, 'login',
                        [
                            'template' => '{input}{label}{error}{hint}',
                            'inputOptions' => ['class' => 'form-control', 'tabindex' => '1']
                        ]
                    );
                    ?>

                <?php endif ?>
                <?php if ($module->debug): ?>
                    <div class="alert alert-warning">
                        <?= Yii::t('user', 'Password is not necessary because the module is in DEBUG mode.'); ?>
                    </div>
                <?php else: ?>
                    <?= $form->field(
                        $model,
                        'password',
                        [

                            'template' => '{input}{label}{error}{hint}',
                            'inputOptions' => ['class' => 'form-control', 'tabindex' => '2']
                        ])
                        ->passwordInput() ?>
                    <?php if ($module->enablePasswordRecovery): ?>
                        <p class="text-right"><?= Html::a('Забыли пароль ?', ['/user/recovery/request'], ['tabindex' => '5', 'class' => 'password-recovery']) ?></p>
                    <?php endif; ?>
                <?php endif ?>
                    <br/>
                    <div class="row">
                        <div class="col-xs-6 text-left">
                            <?= Html::activeCheckbox($model, 'rememberMe', [
                                'label' => '<span>Запомнить меня</span>',
                                'inputOptions' => ['tabindex' => '3'],
                                'labelOptions' => ['class' => 'checkbox-inline checkbox-styled']
                            ]) ?>
                        </div><!--end .col -->
                        <div class="col-xs-6 text-right">
                            <?= Html::submitButton(
                                'Вход',
                                ['class' => 'btn btn-primary btn-block btn-raised', 'tabindex' => '4']
                            ) ?>
                        </div><!--end .col -->
                    </div><!--end .row -->
                <?php ActiveForm::end(); ?>
            </div><!--end .col -->
            <div class="col-sm-5 col-sm-offset-1 text-center">
                <br><br>
                <?php if ($module->enableRegistration): ?>
                    <h3 class="text-light">
                        Нет аккаунта ?
                    </h3>
                    <?= Html::a('Регистрация', ['/user/registration/register'], ['class' => 'btn btn-block btn-raised btn-primary']) ?>
                <?php endif ?>
                <br>
                <br>
                <?php if ($module->enableConfirmation): ?>
                    <h4 class="text-light">
                        Не получили подтверждающее сообщение ?
                    </h4>
                    <?= Html::a('Переслать', ['/user/registration/resend'], ['class' => 'btn btn-sm btn-block btn-raised btn-primary btn-flat']) ?>
                <?php endif ?>
                <?= Connect::widget([
                    'baseAuthUrl' => ['/user/security/auth'],
                ]) ?>
            </div><!--end .col -->
        </div><!--end .row -->
    </div><!--end .card-body -->
</div><!--end .card -->
