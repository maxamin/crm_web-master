<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\widgets\avatar\AvatarWidget;
use yii\widgets\Menu;

/**
 * @var dektrium\user\models\User $user
 * @var \app\models\Profile $model
 */

$user = Yii::$app->user->identity;
?>

<div class="card-body style-primary form-inverse">
    <div class="row">
        <div class="col-xs-12 col-lg-8 hbox-xs">
            <div class="hbox-column width-4">
                <?= AvatarWidget::widget() ?>
            </div><!--end .hbox-column -->

            <div class="hbox-column v-top">
                <div class="clearfix">
                    <div class="col-lg-12">
                        <h3><?= $user->profile->name ?></h3>
                    </div>
                </div>
                <div class="clearfix opacity-75 text-lg">
                    <div class="col-lg-6">
                        <i class="fa fa-user"></i> &nbsp;<?= $user->username ?>
                    </div>
                    <div class="col-lg-6">
                        <i class="fa fa-envelope"></i> &nbsp;<?= $user->email ?>
                    </div>
                    <div class="col-lg-6">
                        <i class="fa fa-map-marker"></i> &nbsp;<?= $user->profile->branch->name ?> (<?= $user->profile->branch->code ?>)
                    </div>
                    <div class="col-lg-6">
                        <i class="fa fa-angle-double-up"></i>
                        <?php

                        $roles = [];

                        foreach (Yii::$app->authManager->getRolesByUser($user->id) as $role) {
                            $roles[] = $role->description;
                        }

                        echo implode(', ', $roles);
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <i class="fa fa-clock-o"></i> &nbsp;Присоеденился <?= Yii::$app->formatter->asDate($user->created_at) ?>
                    </div>
                </div>
            </div><!--end .hbox-column -->
        </div><!--end .hbox-xs -->
    </div><!--end .row -->
</div><!--end .card-body -->
<!-- END DEFAULT FORM ITEMS -->

<!-- BEGIN FORM TABS -->
<div class="card-head style-primary">
    <?= Menu::widget([
        'options' => [
            'class' => 'nav nav-tabs tabs-text-contrast tabs-accent',
        ],
        'items' => [
            ['label' => 'Профиль', 'url' => ['@settings-profile']],
            ['label' => 'Аккаунт', 'url' => ['@settings-account']],
        ],
    ]) ?>
</div><!--end .card-head -->
<!-- END FORM TABS -->
