<?php

/**
 * @var yii\web\View $this
 * @var dektrium\user\Module $module
 */

use yii\helpers\Html;

$this->title = $title;
?>

<div class="card contain-sm style-transparent">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <br/>
                <span class="text-lg text-bold text-primary"><?= Html::encode($this->title) ?></span>
                <br/><br/>
                <?= $this->render('../layouts/partials/_flash') ?>
            </div><!--end .col -->
            <div class="col-sm-5 col-sm-offset-1 text-center">
                <br>
                <br>
                <h3 class="text-light">
                    Хотите войти ?
                </h3>
                <?= Html::a('Авторизация', ['/user/security/login'], ['class' => 'btn btn-block btn-raised btn-primary']) ?>
            </div><!--end .col -->
        </div><!--end .row -->
    </div><!--end .card-body -->
</div><!--end .card -->