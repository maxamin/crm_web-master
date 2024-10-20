<?php

use app\models\LeadStatuses;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use app\models\Users;
use app\models\Products;
use app\models\LeadsProducts;

/* @var $this yii\web\View */
/* @var $model app\models\forms\LeadForm */
/* @var $form yii\widgets\ActiveForm */

$productsTypes = [
    ['id' => 1, 'name' => 'Корпоративный бизнес'],
    ['id' => 2, 'name' => 'Розничный бизнес']
];

?>

<div class="leads-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form floating-label',
            'autocomplete' => 'off',
        ],
    ]); ?>

    <?= $model->errorSummary($form) ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model->leads, 'sum', [
                'template' => '{input}{label}{error}{hint}',
                'inputOptions' => [
                    'class' => 'form-control',
                    'data-inputmask-alias' => 'integer',
                    'data-inputmask-autogroup' => 'true',
                    'data-inputmask-groupseparator' => ',',
                ],
            ])->textInput()->label('Сумма') ?>
        </div>
        <div class="col-sm-6">
            <?php

            $leadStatuses = LeadStatuses::find()->orderBy('ord')->all();

            $options = [];

            foreach ($leadStatuses as $key => $status) {
                $options[$status->id] = [
                    'data-style' => 'background-color:'. $status->color,
                    'data-index' => $key,
                ];
            }

            echo $form->field($model->leads, 'lead_status_id', [
                'template' => '{input}{label}{error}{hint}',
            ])->dropDownList(
                ArrayHelper::map($leadStatuses, 'id', 'name'),
                [
                    'class' => 'form-control lead-status',
                    'options' => $options,
                ]
            )->label('Статус') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model->leads, 'r_user_id', [
                'template' => '{input}{label}{error}{hint}',
            ])->dropDownList(
                ArrayHelper::map(Users::find()->joinWith(['profile'])->orderBy('profile.name')->select(['id', 'profile.name'])->all(), 'id', 'profile.name'),
                ['class' => 'form-control select2-list']
            )->label('Ответственный') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'message')->hiddenInput()->label(false); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-outlined style-primary">
                <div class="card-head">
                    <header><i class="fa fa-fw fa-cart-plus"></i> Продукты</header>
                </div><!--end .card-head -->
                <div class="card-body no-padding">
                    <div class="card-head">
                        <ul class="nav nav-tabs nav-justified" role="tablist">
                            <?php

                            $first = key($productsTypes);

                            foreach ($productsTypes as $typeKey => $type): ?>
                                <li class="<?php if ($first === $typeKey): ?>active<?php endif; ?>"><a href="#productTab<?= $type['id'] ?>" role="tab" data-toggle="tab"><?= $type['name'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="card-body tab-content">
                        <?php

                        $first = key($productsTypes);

                        $leadsProducts = $model->leadsProducts;

                        foreach ($productsTypes as $typeKey => $type): ?>
                            <div role="tabpanel" class="tab-pane<?php if ($first === $typeKey): ?> active<?php endif; ?>" id="productTab<?= $type['id'] ?>">
                                <div class="row">
                                    <?php foreach (Products::find()->active()->where('type = '.$type['id'])->orderBy('name')->all() as $productKey => $product): ?>
                                        <?php

                                        $leadProduct = null;
                                        $key = null;

                                        foreach ($leadsProducts as $leadProductKey => $leadP) {
                                            if ($leadP->product_id == $product->id) {
                                                $leadProduct = $leadP;
                                                $key = $leadProductKey;
                                            }
                                        }

                                        if (!$leadProduct) {
                                            $leadProduct = new LeadsProducts();
                                            $leadProduct->loadDefaultValues();
                                            $key = Yii::$app->security->generateRandomString();
                                        }

                                        ?>

                                        <div class="col-sm-6">
                                            <?= Html::activeCheckbox($leadProduct, '['.$key.']product_id', [
                                                'label' => $product->name,
                                                'value' => $product->id,
                                                'uncheck' => null,
                                                'labelOptions' => ['class' => 'checkbox-inline checkbox-styled btn']
                                            ]); ?>
                                        </div>

                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model->leads, 'comment', ['template' => '{input}{label}{error}{hint}'])
                ->textarea(['class' => 'form-control', 'rows' => 3]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model->leads, 'contacts_id', ['template' => '{input}'])->hiddenInput() ?>
            <?= Html::submitButton($model->leads->isNewRecord ? 'Сохранить' : 'Обновить', [
                'class' => 'pull-right btn btn-flat btn-primary ink-reaction btn-loading-state',
                'data-loading-text' => "<i class='fa fa-spinner fa-spin'></i> Сохранение"
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
