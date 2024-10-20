<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use app\models\Users;
use app\models\Cities;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ContactForm */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="tab-pane active" id="tab1">
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model->natural, 'name', [
                'template' => '{input}{label}{error}{hint}',
                'inputOptions' => [
                    'class' => 'form-control name-search',
                    'data-url' => Url::to(['@natural']),
                    'data-name' => $model->natural->name,
                ],
            ])->textInput(['maxlength' => true])->label('Имя') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model->natural, 'okpo', [
                'template' => '{input}{label}{error}{hint}',
                'inputOptions' => [
                    'class' => 'form-control okpo-search',
                    'data-url' => Url::to(['@natural']),
                    'data-okpo' => $model->natural->okpo,
                    'data-inputmask-alias' => '999999999999',
                    'data-inputmask-placeholder' => '',
                ],
            ])->textInput(['maxlength' => true])->label('ИНН') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div id="searchContactsNameContainer" style="display: none;">
                <div class="card card-bordered style-primary">
                    <div class="card-head">
                        <header><i class="fa fa-fw fa-tag"></i> Похожие физические лица</header>
                        <div class="tools">
                            <a class="btn btn-icon-toggle hide-btn" data-container-selector="#searchContactsNameContainer"><i class="md md-close"></i></a>
                        </div>
                    </div>
                    <div class="card-body height-8 scroll style-default-bright">
                        <div id="searchContactsNameResult"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div id="searchContactsOkpoContainer" style="display: none;">
                <div class="card card-bordered style-danger">
                    <div class="card-head">
                        <header style="font-size: 16px;"><i class="fa fa-fw fa fa-exclamation-triangle"></i> Физическое лицо с таким ИНН уже существует в базе данных</header>
                    </div>
                    <div class="card-body height-4 style-default-bright">
                        <div id="searchContactsOkpoResult"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= Html::activeCheckbox($model->natural, 'active', [
                'label' => 'Активный',
                'labelOptions' => ['class' => 'checkbox-inline checkbox-styled checkbox-success btn']
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model->natural, 'r_user_id', [
                'template' => '{input}{label}{error}{hint}',
            ])->dropDownList(
                ArrayHelper::map(Users::find()->joinWith(['profile'])->orderBy('profile.name')->select(['id', 'profile.name'])->all(), 'id', 'profile.name'),
                ['class' => 'form-control select2-list']
            )->label('Ответственный') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model->natural, 'website', [
                'template' => '{input}{label}{error}{hint}',
                'inputOptions' => ['class' => 'form-control'],
            ])->textInput(['maxlength' => true])->label('Веб-сайт') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model->natural, 'cities_id', [
                'template' => '{input}{label}{error}{hint}',
            ])->dropDownList(
                ArrayHelper::map(Cities::find()->orderBy('name')->select(['id', 'name'])->all(), 'id', 'name'),
                ['class' => 'form-control']
            )->label('Город') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model->natural, 'address', ['template' => '{input}{label}{error}{hint}'])
                ->textarea(['class' => 'form-control', 'rows' => 3])->label('Адрес') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model->natural, 'description', ['template' => '{input}{label}{error}{hint}'])
                ->textarea(['class' => 'form-control', 'rows' => 3]) ?>
        </div>
    </div>
</div><!--end #tab1 -->

<script id="searchContactsNameOkpoTmpl" type="text/x-handlebars-template">
    {{#each contacts}}
    <span style="font-size: 18px; font-weight: bold">{{name}}</span><br>
    <span><b>ИНН: </b>{{okpo}}</span>
    <div class="row text-center" style="padding-top: 20px; border-bottom: 1px solid #ccc">
        <div class="col-md-12" style="padding-bottom: 20px">
            <?= Html::a('Перейти в карточку', Url::to(['@natural-view']).'/{{id}}', ['class' => 'btn btn-accent-light'])  ?>
        </div>
    </div>
    {{/each}}
</script>