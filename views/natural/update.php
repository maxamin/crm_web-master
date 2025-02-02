<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ContactForm */

$this->title = 'Редактирование ' . $model->natural->name;
$this->params['breadcrumbs'][] = ['label' => 'Contacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->natural->name, 'url' => ['view', 'id' => $model->natural->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body ">
                    <div id="rootwizard1" class="form-wizard form-wizard-horizontal">
                        <?= $this->render('_form', [
                            'model' => $model,
                        ]) ?>
                    </div><!--end #rootwizard -->
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
</div>
