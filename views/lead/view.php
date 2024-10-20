<?php

use app\widgets\comments\CommentsWidget;
use app\widgets\leads\LeadsCreateWidget;
use app\widgets\tasks\TasksCreateWidget;
use app\widgets\tasks\TasksGridWidget;
use app\models\LeadsProductStatuses;
use app\models\LeadStatuses;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Leads */

$this->title = $model->getIdentifier();
$this->params['breadcrumbs'][] = ['label' => 'Leads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leads-view">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-head">
                    <header><?= $this->title; ?></header>
                    <div class="tools">
                        <div class="btn-group">
                            <a class="btn btn-icon-toggle btn-collapse"><i class="fa fa-angle-up"></i></a>
                        </div>
                    </div>
                </div><!--end .card-head -->
                <div class="card-body no-padding">
                    <ul class="list">
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Сумма:</small>
                                    <span class="mask" data-inputmask-alias="integer"
                                          data-inputmask-autogroup="true" data-inputmask-groupseparator=","><?= $model->sum ?></span>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-input">
                                <div class="tile-text">
                                    <small>Статус:</small>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <?php

                                        $leadStatuses = LeadStatuses::find()->orderBy('ord')->all();

                                        $options = [];

                                        foreach ($leadStatuses as $key => $status) {
                                            $options[$status->id] = [
                                                'data-style' => 'background-color: ' . $status->color,
                                                'data-id' => $model->id,
                                                'data-status' => $status->id,
                                                'data-index' => $key,
                                            ];
                                        }

                                        echo Html::activeDropDownList($model, 'lead_status_id', ArrayHelper::map($leadStatuses, 'id', 'name'),
                                            [
                                                'class' => 'form-control lead-status change-lead-status',
                                                'options' => $options,
                                            ]);

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Описание Сделки:</small>
                                    <?= $model->comment ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Отделение:</small>
                                    <?= $model->rUser->profile->branch->fullName ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Ответственный:</small>
                                    <?= Html::a($model->rUser->profile->name, ['@user-profile', 'id' => $model->rUser->id]) ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Создал:</small>
                                    <?= Html::a($model->cUser->profile->name, ['@user-profile', 'id' => $model->cUser->id]) ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Дата создания:</small>
                                    <?= Yii::$app->formatter->asDateTime($model->created_at); ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="col-md-12 tile-text text-center">
                                    <a href="<?= Url::to(['update', 'id' => $model->id]) ?>"
                                       class="btn btn-primary">Редактировать</a>
                                    <a href="javascript:void(0);"
                                       class="btn btn-danger delete-model-btn"
                                       data-href="<?= Url::to(['delete', 'id' => $model->id]) ?>"
                                       data-name="<?= Html::encode($model->getIdentifier()) ?>">Удалить</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div><!--end .card-body -->
            </div>
            <?php if ($model->leadsProducts): ?>
                <div class="card card-collapsed">
                    <div class="card-head">
                        <header>Продукты (<?= count($model->leadsProducts) ?>)</header>
                        <div class="tools">
                            <div class="btn-group">
                                <a class="btn btn-icon-toggle btn-collapse"><i class="fa fa-angle-up"></i></a>
                            </div>
                        </div>
                    </div><!--end .card-head -->
                    <div class="card-body no-padding" style="display: none;">
                        <ul class="list">
                            <?php foreach ($model->leadsProducts as $key => $leadProduct): ?>
                                <li class="tile">
                                    <div class="tile-content">
                                        <div class="tile-text">
                                            <small><?= $leadProduct->product->name ?>:</small>
                                            <?php

                                            $productStatuses = LeadsProductStatuses::find()->all();

                                            $options = [];

                                            foreach ($productStatuses as $key => $status) {
                                                $options[$status->id] = [
                                                    'data-style' => 'color: ' . $status->color,
                                                    'data-id' => $leadProduct->id,
                                                    'data-status' => $status->id,
                                                    'data-index' => $key,
                                                ];
                                            }

                                            echo Html::activeDropDownList($leadProduct, 'status', ArrayHelper::map($productStatuses, 'id', 'name'),
                                                [
                                                    'class' => 'form-control product-status change-product-status',
                                                    'options' => $options,
                                                ]);

                                            ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div><!--end .card-body -->
                </div>
            <?php endif; ?>
            <?php if ($model->contacts): ?>
                <div class="card card-collapsed">
                    <div class="card-head">
                        <header><?= $model->contacts->getIdentifier() ?></header>
                        <div class="tools">
                            <div class="btn-group">
                                <a class="btn btn-icon-toggle btn-collapse"><i class="fa fa-angle-up"></i></a>
                            </div>
                        </div>
                    </div><!--end .card-head -->
                    <div class="card-body no-padding" style="display: none">
                        <ul class="list">
                            <li class="tile">
                                <div class="tile-content">
                                    <div class="tile-text">
                                        <small>ИНН/ОКПО:</small>
                                        <?= $model->contacts->okpo ?>
                                    </div>
                                </div>
                            </li>
                            <li class="tile">
                                <div class="tile-content">
                                    <div class="tile-text">
                                        <small>Город:</small>
                                        <?= $model->contacts->cities->name ?>
                                    </div>
                                </div>
                            </li>
                            <li class="tile">
                                <div class="tile-content">
                                    <div class="tile-text">
                                        <small>Ответственный:</small>
                                        <?= $model->contacts->rUser->profile->name ?>
                                    </div>
                                </div>
                            </li>
                            <li class="tile">
                                <div class="tile-content">
                                    <div class="col-md-12 tile-text text-center">
                                        <?= Html::a('Перейти в карточку', ['@' . $model->contacts->getTypeLabel() . '-view', 'id' => $model->contacts->id], ['class' => 'btn btn-primary']) ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div><!--end .card-body -->
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-head">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        <li class="active"><a href="#tasksWidget">Задачи</a></li>
                        <li class=""><a href="#commentsWidget">Заметки</a></li>
                    </ul>
                </div><!--end .card-head -->
                <div class="card-body tab-content">
                    <div class="tab-pane active" id="tasksWidget">
                        <?= TasksCreateWidget::widget([
                            'model' => $model,
                        ]) ?>
                        <?= TasksGridWidget::widget([
                            'model' => $model,
                        ]); ?>
                    </div>
                    <div class="tab-pane" id="commentsWidget">
                        <?= CommentsWidget::widget([
                            'model' => $model,
                        ]); ?>
                    </div>
                </div><!--end .card-body -->
            </div>
        </div>
    </div>
</div>

