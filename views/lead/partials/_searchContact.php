<?php

use app\models\Contacts;
use app\models\RelationTypes;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\forms\LeadForm */
/* @var $form yii\widgets\ActiveForm */

?>

<div id="searchContact" style="<?php if ($model->leads->contacts): ?>display: none;<?php endif;?>">
    <div class="row">
        <div class="col-sm-12">
            <div class="form floating-label">
                <div class="col-sm-3">
                    <div class="form-group">
                        <?php

                        $relationTypes = RelationTypes::find()->byAliases(Contacts::typeLabels())->all();

                        $options = [];

                        foreach ($relationTypes as $key => $type) {
                            $options[$type->id] = [
                                'data-url' => Url::to('@' . $type->alias),
                                'data-url-view' => Url::to('@' . $type->alias . '-view'),
                                'data-alias' => $type->alias,
                            ];
                        }

                        echo Html::DropDownList('contact_type', null, ArrayHelper::map($relationTypes, 'id', 'name'),
                            [
                                'id' => 'searchContactType',
                                'class' => 'form-control',
                                'options' => $options,
                            ]);

                        ?>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group">
                        <?= Html::textInput('search_contact', null, [
                            'id' => 'searchContactInput',
                            'class' => 'form-control',
                        ]); ?>
                        <?= Html::label('Поиск', 'search_contact'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div id="searchContactsContainer" style="display: none;">
                <div class="card card-bordered style-primary">
                    <div class="card-head">
                        <header><i class="fa fa-fw fa-tag"></i> Похожие лица</header>
                        <div class="tools">
                            <a class="btn btn-icon-toggle hide-btn" data-container-selector="#searchContactsContainer"><i
                                        class="md md-close"></i></a>
                        </div>
                    </div>
                    <div class="card-body height-8 scroll style-default-bright">
                        <div id="searchContactsResult"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="viewContact" style="<?php if (!$model->leads->contacts): ?>display: none;<?php endif;?>">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body no-padding">
                    <ul class="list divider-full-bleed">
                        <div id="contactsContainer">
                            <?php if (isset($model->leads->contacts)): ?>
                                <li class="tile lead-contact">
                                    <a class="tile-content ink-reaction"
                                       href="<?php

                                       $alias = '@' . $model->leads->contacts->getTypeLabel() . '-view';

                                       echo Url::to([$alias, 'id' => $model->leads->contacts->id]) ?>">
                                        <div class="tile-text">
                                            <?= $model->leads->contacts->getIdentifier() ?>
                                            <small>ИНН/ОКПО: <?= $model->leads->contacts->okpo ?></small>
                                        </div>
                                    </a>
                                    <a class="btn btn-flat ink-reaction remove-btn remove-lead-contact"
                                       data-container-selector=".lead-contact">
                                        <i class="md md-highlight-remove"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </div>
                    </ul>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
</div>

<script id="contactTmpl" type="text/x-handlebars-template">
    <li class="tile lead-contact">
        <a class="tile-content ink-reaction" href="<?= Url::to('{{url}}'); ?>">
            <div class="tile-text">
                {{name}}
                <small>ИНН/ОКПО: {{okpo}}</small>
            </div>
        </a>
        <a class="btn btn-flat ink-reaction remove-btn remove-lead-contact" data-container-selector=".lead-contact">
            <i class="md md-highlight-remove"></i>
        </a>
    </li>
</script>

<script id="searchContactsTmpl" type="text/x-handlebars-template">
    {{#each contacts}}
    <span style="font-size: 18px; font-weight: bold">{{name}}</span><br>
    <span><b>ИНН/ОКПО: </b>{{okpo}}</span>
    <div class="row text-center" style="padding-top: 20px; border-bottom: 1px solid #ccc">
        <div class="col-md-12" style="padding-bottom: 20px">
            <a href="javascript:void(0);" class="btn btn-accent-light link-lead-contact" data-id="{{id}}"
               data-name="{{name}}" data-okpo="{{okpo}}">Связать</a>
        </div>
        <div class="col-md-12" style="padding-bottom: 20px">
            <?= Html::a('Перейти в карточку', '{{url}}', ['class' => 'btn btn-primary']);  ?>
        </div>
    </div>
    {{/each}}
</script>