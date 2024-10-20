<?php

use app\widgets\comments\CommentsWidget;
use app\widgets\leads\LeadsCreateWidget;
use app\widgets\leads\LeadsGridWidget;
use app\widgets\tasks\TasksCreateWidget;
use app\widgets\tasks\TasksGridWidget;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Legal */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Contacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-head">
                <header><?php echo $this->title; ?></header>
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
                                <small>ОКПО:</small>
                                <?= $model->okpo ?>
                            </div>
                        </div>
                    </li>
                    <li class="tile">
                        <div class="tile-content">
                            <div class="tile-text">
                                <small>Веб-сайт:</small>
                                <a href="<?= $model->website ?>" target="_blank"><?= $model->website ?></a>
                            </div>
                        </div>
                    </li>
                    <li class="tile">
                        <div class="tile-content">
                            <div class="tile-text">
                                <small>Город:</small>
                                <?= $model->cities->name ?>
                            </div>
                        </div>
                    </li>
                    <li class="tile">
                        <div class="tile-content">
                            <div class="tile-text">
                                <small>Адрес:</small>
                                <?= $model->address ?>
                            </div>
                        </div>
                    </li>
                    <li class="tile">
                        <div class="tile-content">
                            <div class="tile-text">
                                <small>Описание Клиента:</small>
                                <?= $model->description ?>
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
                                   data-name="<?= Html::encode($model->name) ?>">Удалить</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div><!--end .card-body -->
        </div>
        <?php if($model->contactsInfos): ?>
            <div class="card card-collapsed">
                <div class="card-head">
                    <header>Контактная информация</header>
                    <div class="tools">
                        <div class="btn-group">
                            <a class="btn btn-icon-toggle btn-collapse"><i class="fa fa-angle-up"></i></a>
                        </div>
                    </div>
                </div><!--end .card-head -->
                <div class="card-body no-padding" style="display: none;">
                    <ul class="list">
                        <?php foreach ($model->contactsInfos as $key => $value): ?>
                            <li class="tile">
                                <div class="tile-content">
                                    <div class="tile-text">
                                        <small><?= $value->contactType->name ?>:</small>
                                        <?= $value->contact_value ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div><!--end .card-body -->
            </div>
        <?php endif; ?>
        <?php if($model->contactsLinks): ?>
            <div class="card card-collapsed">
                <div class="card-head">
                    <header>Связанные физ. лица</header>
                    <div class="tools">
                        <div class="btn-group">
                            <a class="btn btn-icon-toggle btn-collapse"><i class="fa fa-angle-up"></i></a>
                        </div>
                    </div>
                </div><!--end .card-head -->
                <div class="card-body no-padding" style="display: none;">
                    <ul class="list">
                        <?php foreach ($model->contactsLinks as $key => $value): ?>
                            <li id="link-<?= $key ?>" class="tile">
                                <a class="tile-content ink-reaction"
                                   href="<?= Url::to(['@' . $value->contacts->getTypeLabel() . '-view', 'id' => $value->contacts->id]); ?>">
                                    <div class="tile-text">
                                        <?= $value->contacts->getIdentifier() ?>
                                        <small>ИНН: <?= $value->contacts->okpo ?></small>
                                    </div>
                                </a>
                                <a class="btn btn-flat ink-reaction btn-unlink" href="javascript:void(0);"
                                   data-href="<?= Url::to(['unlink', 'id' => $value->id]) ?>" data-name="<?= Html::encode($value->contacts->getIdentifier()) ?>" data-container-selector="#link-<?= $key ?>">
                                    <i class="md md-highlight-remove"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div><!--end .card-body -->
            </div>
        <?php endif; ?>
        <?php if ($model->additionalValues): ?>
            <div class="card card-collapsed">
                <div class="card-head">
                    <header>Дополнительные параметры</header>
                    <div class="tools">
                        <div class="btn-group">
                            <a class="btn btn-icon-toggle btn-collapse"><i class="fa fa-angle-up"></i></a>
                        </div>
                    </div>
                </div><!--end .card-head -->
                <div class="card-body no-padding" style="display: none;">
                    <ul class="list">
                        <?php foreach ($model->additionalValues as $key => $value): ?>
                            <li class="tile">
                                <div class="tile-content">
                                    <div class="tile-text">
                                        <small><?= $value->additionField->name ?>:</small>
                                        <?php if ($value->additionField->is_directory) {
                                            echo \app\models\AdditionalValuesDirectories::find()->where(['addition_fields_id' => $value->additionField->id, 'value' => $value->value])->one()->name;
                                        } else {
                                            echo $value->value;
                                        } ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div><!--end .card-body -->
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-head">
                <ul class="nav nav-tabs" data-toggle="tabs">
                    <li class="active"><a href="#leadsWidget">Сделки</a></li>
                    <li class=""><a href="#tasksWidget">Задачи</a></li>
                    <li class=""><a href="#commentsWidget">Заметки</a></li>
                </ul>
            </div><!--end .card-head -->
            <div class="card-body tab-content">
                <div class="tab-pane active" id="leadsWidget">
                    <?= LeadsCreateWidget::widget([
                        'model' => $model,
                    ]) ?>
                    <?= LeadsGridWidget::widget([
                        'model' => $model,
                    ]); ?>
                </div>
                <div class="tab-pane" id="tasksWidget">
                    <?= TasksCreateWidget::widget([
                        'model' => $model,
                    ]); ?>
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
