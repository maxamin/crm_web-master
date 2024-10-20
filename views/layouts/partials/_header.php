<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
?>


<!-- BEGIN HEADER-->
<header id="header">
    <div class="headerbar">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="headerbar-left">
            <ul class="header-nav header-nav-options">
                <li class="header-nav-brand">
                    <div class="brand-holder">
                        <a href="">
                            <span class="text-lg text-bold text-primary">CONCORD CRM</span>
                        </a>
                    </div>
                </li>
                <li>
                    <a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="headerbar-right">
            <ul class="header-nav header-nav-profile">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown" aria-expanded="false">
                        <?= Html::img(Yii::$app->user->identity->profile->getAvatarUrl(200), [
                            'alt' => Yii::$app->user->identity->username,
                        ]) ?>
                        <span class="profile-info">
                            <?= Yii::$app->user->identity->profile->name ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu animation-dock">
                        <li class="dropdown-header">Настройки</li>
                        <li><a href="<?= Url::toRoute('@settings-profile'); ?>"><i
                                        class="fa fa-fw fa-user text-primary"></i> Профиль</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::toRoute('@security-logout'); ?>" data-method="post"><i
                                        class="fa fa-fw fa-power-off text-danger"></i> Выход</a></li>
                    </ul>
                    <!--end .dropdown-menu -->
                </li>
                <!--end .dropdown -->
            </ul>
                <!--end .header-nav-profile -->
                <!--
                <ul class="header-nav header-nav-toggle">
                    <li>
                        <a class="btn btn-icon-toggle btn-default" href="#offcanvas-search" data-toggle="offcanvas"
                           data-backdrop="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                    </li>
                </ul>
                -->
                <!--end .header-nav-toggle -->
        </div>
        <!--end #header-navbar-collapse -->
    </div>
</header>
<!-- END HEADER-->