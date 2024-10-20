<?php

use Yii;
use app\models\Leads;
use app\models\LeadStatuses;
use yii\helpers\Url;

/* @var $this yii\web\View */

?>

<div class="row">
    <div class="col-md-8">
        <h2 class="text-primary">Сделки в работе</h2>
    </div>
    <div class="col-md-4" style="text-align: right;">
        <a href='<?= Url::to('@leads-create') ?>' class="btn btn-primary" style="margin-top: 13px;">Добавить сделку</a>
    </div>
</div>
<div class="row leads-sortable-container">
    <?php

    $ls = LeadStatuses::find()->ordered()->inWork()->all();

    $lsCount = count($ls);

    $col = 'col-sm-' . (($lsCount >= 4) ? 3 : (12/$lsCount));

    foreach ($ls as $key => $status): ?>
        <div class="<?= $col ?>">
            <div class="card">
                <div class="card-head" style="background-color: <?= $status->color ?>">
                    <header><span class="status-name"><?= $status->name ?></span></header>
                </div>
                <div class="card-body no-padding style-default-bright">
                    <ul class="list divider-full-bleed leads-sortable height-6"
                        data-status-id="<?= $status->id ?>">
                        <?php if ($leads = Leads::find()->andWhere([
                                'r_user_id' => Yii::$app->user->id, 'lead_status_id' => $status->id,
                            ])->newest()->all()): ?>
                            <?php foreach ($leads as $leadKey => $lead): ?>
                                <li class="tile as-link model-row"
                                    data-id="<?= $lead->id ?>"
                                    data-href="<?= Url::to(['@leads-view', 'id' => $lead->id]) ?>"
                                    data-name="<?= $lead->getIdentifier() ?>"
                                    data-delete="<?= Url::to(['@leads-delete', 'id' => $lead->id]) ?>"
                                    data-update="<?= Url::to(['@leads-update', 'id' => $lead->id]) ?>">
                                    <div class="tile-content ink-reaction">
                                        <div class="tile-text">
                                            <?= $lead->getIdentifier() ?>
                                            <small>
                                                Лицо: <?= $lead->contacts->getIdentifier() ?>
                                            </small>
                                            <small>
                                                Создана: <?= Yii::$app->formatter->asDatetime($lead->created_at) ?>
                                            </small>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div><!--end .card -->
        </div>
    <?php endforeach; ?>
</div>