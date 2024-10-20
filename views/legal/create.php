<?php

/* @var $this yii\web\View */
/* @var $model app\models\Contacts */

$this->title = 'Добавление юридического лица';
$this->params['breadcrumbs'][] = ['label' => 'Contacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-create">
    <div class="row">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
