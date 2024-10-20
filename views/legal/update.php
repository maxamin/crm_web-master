<?php

/* @var $this yii\web\View */
/* @var $model app\models\Contacts */

$this->title = 'Редактирование ' . $model->legal->name;
$this->params['breadcrumbs'][] = ['label' => 'Contacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->legal->name, 'url' => ['view', 'id' => $model->legal->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-update">
    <div class="row">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
