<?php

use app\models\RelationTypes;
use app\models\Tasks;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */
/* @var $form yii\widgets\ActiveForm */

?>

<div id="searchRelated" style="<?php if ($model->related): ?>display: none;<?php endif;?>">
    <div class="row">
        <div class="col-sm-12">
            <div class="form floating-label">
                <div class="col-sm-3">
                    <div class="form-group">
                        <?php

                        $relationTypes = RelationTypes::find()->byClassMap(Tasks::morphMap())->all();

                        $options = [];

                        foreach ($relationTypes as $key => $type) {
                            $options[$type->id] = [
                                'data-url' => Url::to('@' . $type->alias),
                                'data-url-view' => Url::to('@' . $type->alias . '-view'),
                                'data-alias' => $type->alias,
                            ];
                        }

                        echo Html::DropDownList('relation_type', null, ArrayHelper::map($relationTypes, 'id', 'name'),
                            [
                                'id' => 'searchRelatedType',
                                'class' => 'form-control',
                                'options' => $options,
                            ]);

                        ?>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group">
                        <?= Html::textInput('search_related', null, [
                            'id' => 'searchRelatedInput',
                            'class' => 'form-control',
                        ]); ?>
                        <?= Html::label('Поиск', 'search_related'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div id="searchRelatedContainer" style="display: none;">
                <div class="card card-bordered style-primary">
                    <div class="card-head">
                        <header><i class="fa fa-fw fa-tag"></i> Похожие </header>
                        <div class="tools">
                            <a class="btn btn-icon-toggle hide-btn" data-container-selector="#searchRelatedContainer"><i
                                        class="md md-close"></i></a>
                        </div>
                    </div>
                    <div class="card-body height-8 scroll style-default-bright">
                        <div id="searchRelatedResult"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="viewRelated" style="<?php if (!$model->related): ?>display: none;<?php endif;?>">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body no-padding">
                    <ul class="list divider-full-bleed">
                        <li class="tile task-related">
                            <a id="relatedLinkView" class="tile-content ink-reaction"
                               href="<?= $model->related ? Url::to('@' . $model->relationType->alias . '-view') . '/' . $model->related->id : 'javascript:void(0);' ?>">
                                <div class="tile-text">
                                    <span id="relatedName"><?= $model->related ? $model->related->getIdentifier() : '' ?></span>
                                    <small id="relatedType"><?= $model->related ? $model->relationType->name : '' ?></small>
                                </div>
                            </a>
                            <a class="btn btn-flat ink-reaction hide-btn remove-task-related"
                               data-container-selector="#viewRelated">
                                <i class="md md-highlight-remove"></i>
                            </a>
                        </li>
                    </ul>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
</div>

<script id="searchRelatedTmpl" type="text/x-handlebars-template">
    {{#each related}}
    <span style="font-size: 18px; font-weight: bold">{{name}}</span><br>
    <div class="row text-center" style="padding-top: 20px; border-bottom: 1px solid #ccc">
        <div class="col-md-12" style="padding-bottom: 20px">
            <a href="javascript:void(0);" class="btn btn-accent-light link-task-related" data-id="{{id}}"
               data-name="{{name}}" data-url="{{url}}">Связать</a>
        </div>
        <div class="col-md-12" style="padding-bottom: 20px">
            <a href="{{url}}" class ='btn btn-primary'>Перейти в карточку</a>
        </div>
    </div>
    {{/each}}
</script>