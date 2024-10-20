<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \app\models\Profile $profile
 */

$this->title = empty($profile->name) ? Html::encode($profile->user->username) : Html::encode($profile->name);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="section-body contain-lg">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- BEGIN DEFAULT FORM ITEMS -->
                <div class="card-body style-primary form-inverse">
                    <div class="row">
                        <div class="col-xs-12 col-lg-8 hbox-xs">
                            <div class="hbox-column width-4">
                                <?= Html::img($profile->getAvatarUrl(), [
                                    'class' => 'img-circle border-white border-xl img-responsive auto-width',
                                    'alt' => $profile->user->username,
                                ]); ?>
                            </div><!--end .hbox-column -->

                            <div class="hbox-column v-top">
                                <div class="clearfix">
                                    <div class="col-lg-12">
                                        <h3><?= $profile->name ?></h3>
                                    </div>
                                </div>
                                <div class="clearfix opacity-75 text-lg">
                                    <div class="col-lg-12">
                                        <i class="fa fa-map-marker"></i>
                                        <?= $profile->branch->name ?> (<?= $profile->branch->code ?>)
                                    </div>
                                    <div class="col-lg-12">
                                        <i class="fa fa-angle-double-up"></i>
                                        <?php

                                        $roles = [];

                                        foreach (Yii::$app->authManager->getRolesByUser($profile->user->id) as $role) {
                                            $roles[] = $role->description;
                                        }

                                        echo implode(', ', $roles);
                                        ?>
                                    </div>
                                    <div class="col-lg-12">
                                        <i class="fa fa-clock-o"></i>
                                        Присоеденился <?= Yii::$app->formatter->asDate($profile->user->created_at) ?>
                                    </div>
                                </div>
                            </div><!--end .hbox-column -->
                        </div><!--end .hbox-xs -->
                    </div><!--end .row -->
                </div>
                <!-- BEGIN FORM TAB PANES -->
                <div class="card-body">
                    <div class="opacity-75 text-primary">
                        <h4>Контактная информация</h4>
                    </div>
                    <br>
                    <ul class="list divider-full-bleed">
                        <li class="tile">
                            <div class="tile-content no-padding">
                                <div class="tile-text">
                                    <small>Контактный Email</small>
                                    <a href="mailto:<?= $profile->getContactEmail() ?>">
                                        <?= $profile->getContactEmail() ?>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?php if (isset($profile->contact_phone)): ?>
                        <li class="tile">
                            <div class="tile-content no-padding">
                                <div class="tile-text">
                                    <small>Контактный телефон</small>
                                    <span class="mask" data-inputmask-alias="phone">
                                        <?= $profile->contact_phone ?></span>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php if (isset($profile->internal_phone)): ?>
                        <li class="tile">
                            <div class="tile-content no-padding">
                                <div class="tile-text">
                                    <small>Внутренний телефон</small> <?= $profile->internal_phone ?>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div><!--end .card-body.tab-content -->
                <!-- END FORM TAB PANES -->
            </div><!--end .card -->
        </div><!--end .col -->
        <!-- END ADD CONTACTS FORM -->
    </div>
</div>
