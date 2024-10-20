<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $comments \app\models\Comments */

?>

<div class="comments-widget">
    <div class="row">
        <div class="col-sm-12">
            <?php Pjax::begin([
                'id' => 'widgetPjaxCommentsList',
                'enablePushState' => false,
                'clientOptions' => [
                    'method' => 'POST'
                ],
            ]) ?>
            <!-- BEGIN MESSAGE ACTIVITY -->
            <?php if ($count > 0): ?>
                <?php if (isset($showMore)) {

                    $url = '?show=' . ($showMore ? '1' : '0');

                    echo Html::a($showMore ? 'Показать все (' . $count . ')' : 'Скрыть',  $url, [
                        'class' => 'btn btn-block btn-default-light',
                        'id' => 'showMoreLink',
                    ]);
                }?>
                <div class="tab-pane" id="activity">
                    <ul class="timeline collapse-lg timeline-hairline no-margin">
                        <?php foreach ($comments as $key => $comment): ?>
                            <li>
                                <div class="timeline-circ circ-xl style-primary"><i class="md md-comment"></i></div>
                                <div class="timeline-entry">
                                    <div class="card style-default-light">
                                        <div class="card-body small-padding">
                                            <?= Html::img($comment->cUser->profile->getAvatarUrl(200), [
                                                'class' => 'img-circle img-responsive pull-left width-1',
                                                'alt' => $comment->cUser->username,
                                            ]) ?>
                                            <span class="text-medium"><?= Html::a($comment->cUser->profile->name, ['@user-profile', 'id' => $comment->cUser->id]) ?></span><br/>
                                            <span class="opacity-50"><?= Yii::$app->formatter->asDatetime($comment->created_at) ?></span>
                                        </div>
                                        <div class="card-body">
                                            <p><?= $comment->body ?></p>
                                        </div>
                                    </div>
                                </div><!--end .timeline-entry -->
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div><!--end #activity -->
            <?php endif; ?>
            <?php Pjax::end() ?>

            <?php Pjax::begin([
                'id' => 'pjaxCreateCommentWidget',
                'options' => [
                    'class' => 'widget-pjax-create',
                    'data-reload-container' => '#widgetPjaxCommentsList',
                ],
            ]) ?>
            <?php $form = ActiveForm::begin([
                'options' => [
                    'class' => 'form floating-label',
                    'autocomplete' => 'off',
                    'data-pjax' => true,
                ],
            ]); ?>

            <?= $form->errorSummary($commentForm, [
                'header' => '<p><i class="fa fa-fw fa-exclamation-triangle"></i>При попытке сохранения были обнаружены следующие ошибки:</p>',
            ]) ?>

            <div class="card no-margin">
                <div class="card-body">
                    <?= $form->field($commentForm, 'body', ['template' => '{input}{label}{error}{hint}'])
                        ->textarea(['class' => 'form-control autosize', 'rows' => 2])->label('Заметка') ?>
                </div><!--end .card-body -->
                <div class="card-actionbar">
                    <div class="card-actionbar-row">
                        <?= Html::submitButton('Создать', [
                            'class' => 'btn btn-flat btn-primary ink-reaction',
                        ]) ?>
                    </div><!--end .card-actionbar-row -->
                </div><!--end .card-actionbar -->
            </div><!--end .card -->
            <?php ActiveForm::end(); ?>
            <?php Pjax::end(); ?>
            <!-- BEGIN ENTER MESSAGE -->
        </div>
    </div>
</div>
