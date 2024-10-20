<?php

use app\widgets\comments\CommentsWidget;
use kartik\popover\PopoverX;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */

$this->title = 'Задача #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="tasks-view">
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
                <div id="task<?= $model->id ?>" class="card-body no-padding task-container">
                    <ul class="list">
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Тип:</small>
                                    <?= '<label class="radio-inline radio-styled '
                                    . $model->type->css . '"><input type="radio" checked><span>'
                                    . $model->type->name . '</span></label>' ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Дата/Время:</small>
                                    <?= $model->dateTime ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Статус:</small>
                                    <?= $model->status ? '<span class="task-status closed">Закрыта</span>'
                                        : '<span class="task-status opened">Открыта</span>' ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Описание:</small>
                                    <?= $model->comment ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Отделение:</small>
                                    <?= $model->user->profile->branch->fullName ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Ответственный:</small>
                                    <?= Html::a($model->user->profile->name,
                                        ['@user-profile', 'id' => $model->user->id]) ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Поставил:</small>
                                    <?= Html::a($model->cUser->profile->name,
                                        ['@user-profile', 'id' => $model->cUser->id]) ?>
                                </div>
                            </div>
                        </li>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Закрыл:</small>
                                    <span class="close-user">
                                        <?php if ($model->status === $model::STATUS_CLOSED) {
                                            echo $model->closeUser->profile->name;
                                        } else {

                                            ob_start();
                                            PopoverX::begin([
                                                'header' => '<i class="md md-comment"></i>',
                                                'type' => PopoverX::TYPE_DEFAULT,
                                                'placement' => PopoverX::ALIGN_RIGHT,
                                                'size' => PopoverX::SIZE_LARGE,

                                                'toggleButton' => [
                                                    'label' => 'Закрыть',
                                                    'class'=>'btn btn-flat btn-primary ink-reaction'
                                                ],
                                                'footer' => false,
                                            ]);

                                            echo Html::beginForm(Url::to(['@tasks-close', 'id' => $model->id]), 'post', [
                                                'class' => 'form close-task-form',
                                                'data-task-container-selector' => '#task' . $model->id,
                                            ]);

                                            echo Html::textarea('close_comment', null, [
                                                'class' => 'form-control autosize',
                                                'placeholder' => 'Результат',
                                                'rows' => 3,
                                            ]);

                                            echo Html::beginTag('div', ['class' => 'popover-buttons-group  pull-right']);

                                            echo Html::submitButton('Закрыть', [
                                                'class' => 'btn btn-primary',
                                            ]);

                                            echo Html::button('Отмена', [
                                                'class'=>'btn btn-default',
                                                'data-dismiss' => 'popover-x',
                                            ]);

                                            echo Html::endTag('div');

                                            echo Html::endForm();

                                            PopoverX::end();
                                            echo ob_get_clean();

                                        } ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                        <?php if ($model->status === $model::STATUS_CLOSED): ?>
                            <li class="tile">
                                <div class="tile-content">
                                    <div class="tile-text">
                                        <small>Результат:</small>
                                        <?= $model->close_comment ?>
                                    </div>
                                </div>
                            </li>
                        <?php endif ?>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="tile-text">
                                    <small>Дата создания:</small>
                                    <?= Yii::$app->formatter->asDateTime($model->created_at); ?>
                                </div>
                            </div>
                        </li>
                        <?php if ($model->status === $model::STATUS_CLOSED): ?>
                            <li class="tile">
                                <div class="tile-content">
                                    <div class="tile-text">
                                        <small>Дата закрытия:</small>
                                        <?= Yii::$app->formatter->asDateTime($model->closed_at); ?>
                                    </div>
                                </div>
                            </li>
                        <?php endif ?>
                        <li class="tile">
                            <div class="tile-content">
                                <div class="col-md-12 tile-text text-center">
                                    <a href="<?= Url::to(['update', 'id' => $model->id]) ?>"
                                       class="btn btn-primary">Редактировать</a>
                                    <a href="javascript:void(0);"
                                       class="btn btn-danger delete-model-btn"
                                       data-href="<?= Url::to(['delete', 'id' => $model->id]) ?>"
                                       data-name="<?= Html::encode('Задача #' . $model->id) ?>">Удалить</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div><!--end .card-body -->
            </div>
            <?php if ($model->related): ?>
                <div class="card card-collapsed">
                    <div class="card-head">
                        <header><?= $model->related->getIdentifier() ?></header>
                        <div class="tools">
                            <div class="btn-group">
                                <a class="btn btn-icon-toggle btn-collapse"><i class="fa fa-angle-up"></i></a>
                            </div>
                        </div>
                    </div><!--end .card-head -->
                    <div class="card-body no-padding" style="display: none">
                        <ul class="list">
                            <?php if ($model->related instanceof \app\models\Contacts): ?>
                                <li class="tile">
                                    <div class="tile-content">
                                        <div class="tile-text">
                                            <small>ИНН/ОКПО:</small>
                                            <?= $model->related->okpo ?>
                                        </div>
                                    </div>
                                </li>
                                <li class="tile">
                                    <div class="tile-content">
                                        <div class="tile-text">
                                            <small>Город:</small>
                                            <?= $model->related->cities->name ?>
                                        </div>
                                    </div>
                                </li>
                            <?php elseif ($model->related instanceOf \app\models\Leads): ?>
                                <li class="tile">
                                    <div class="tile-content">
                                        <div class="tile-text">
                                            <small>Сумма:</small>
                                            <span class="mask" data-inputmask-alias="integer"
                                                  data-inputmask-autogroup="true" data-inputmask-groupseparator=","><?= $model->related->sum ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="tile">
                                    <div class="tile-content">
                                        <div class="tile-text">
                                            <small>Продуктов:</small>
                                            <?= count($model->related->leadsProducts) ?>
                                        </div>
                                    </div>
                                </li>
                                <li class="tile">
                                    <div class="tile-content">
                                        <div class="tile-text">
                                            <small>Cтатус:</small>
                                            <span class="btn"
                                                  style="background-color: <?= $model->related->leadStatus->color ?>"><span
                                                        class="status-name"><?= $model->related->leadStatus->name ?></span></span>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <li class="tile">
                                <div class="tile-content">
                                    <div class="tile-text">
                                        <small>Ответственный:</small>
                                        <?= $model->related->rUser->profile->name ?>
                                    </div>
                                </div>
                            </li>
                            <li class="tile">
                                <div class="tile-content">
                                    <div class="col-md-12 tile-text text-center">
                                        <?= Html::a('Перейти в карточку',
                                            ['@' . $model->relationType->alias . '-view', 'id' => $model->related->id],
                                            ['class' => 'btn btn-primary']) ?>
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
                        <li class="active"><a href="#commentsWidget">Заметки</a></li>
                    </ul>
                </div><!--end .card-head -->
                <div class="card-body tab-content">
                    <div class="tab-pane active" id="commentsWidget">
                        <?= CommentsWidget::widget([
                            'model' => $model,
                        ]); ?>
                    </div>
                </div><!--end .card-body -->
            </div>
        </div>
    </div>
</div>
