<?php

use app\models\Users;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\forms\NaturalImportForm */

$this->title = 'Импорт физических лиц';
$this->params['breadcrumbs'][] = ['label' => 'Contacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form',
                            'autocomplete' => 'off',
                            'enctype' => 'multipart/form-data',
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $model->errorSummary($form) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'file', [
                                'template' => '{input}{label}{error}{hint}',
                            ])->fileInput(['class' => 'form-control']); ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'r_user_id', [
                                'template' => '{input}{label}{error}{hint}',
                            ])->dropDownList(
                                ArrayHelper::map(
                                    Users::find()
                                        ->joinWith(['profile'])
                                        ->orderBy('profile.name')
                                        ->select(['id', 'profile.name'])
                                        ->all(), 'id', 'profile.name'),
                                ['class' => 'form-control select2-list']
                            ) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Импортировать', [
                                'class' => 'pull-right btn btn-flat btn-primary ink-reaction btn-loading-state',
                                'data-loading-text' => "<i class='fa fa-spinner fa-spin'></i> Импортирование"
                            ]) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
</div>
