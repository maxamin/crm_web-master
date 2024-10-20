<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\forms\LeadForm */

$this->title = 'Обновление сделки ' . $model->leads->getIdentifier();
$this->params['breadcrumbs'][] = ['label' => 'Leads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->leads->getIdentifier(), 'url' => ['view', 'id' => $model->leads->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="leads-update">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-head style-primary">
                    <header><?= $this->title ?></header>
                </div>
                <div class="card-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-head style-primary">
                    <header>Юр.\Физ. лицо</header>
                </div>
                <div class="card-body">
                    <?= $this->render('partials/_searchContact', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
