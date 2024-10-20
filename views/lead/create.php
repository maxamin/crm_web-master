<?php

/* @var $this yii\web\View */
/* @var $model app\models\forms\LeadForm */

$this->title = 'Добавление сделки';
$this->params['breadcrumbs'][] = ['label' => 'Leads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leads-create">
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
