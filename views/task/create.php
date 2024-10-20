<?php

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */

$this->title = 'Добавление задачи';
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-create">
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
                    <header>Отношение</header>
                </div>
                <div class="card-body">
                    <?= $this->render('partials/_searchRelated', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
