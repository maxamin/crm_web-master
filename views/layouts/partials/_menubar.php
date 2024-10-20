<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\widgets\Menu;

?>

<!-- BEGIN MENUBAR-->
<div id="menubar" class="menubar-inverse ">
    <div class="menubar-fixed-panel">
        <div>
            <a class="btn btn-icon-toggle btn-default menubar-toggle" data-toggle="menubar"
               href="javascript:void(0);">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <div class="expanded">
            <a href="/">
                <span class="text-lg text-bold text-primary ">MATERIAL&nbsp;ADMIN</span>
            </a>
        </div>
    </div>


    <div class="menubar-scroll-panel">
        <!-- BEGIN MAIN MENU -->

        <?php

        $isActive = function ($url) {
            $c = explode('/', $url);

            $result = false;

            $controller = Yii::$app->controller->id;
            $module = Yii::$app->module->id ?? null;
            $action = Yii::$app->controller->action->id;

            switch (count($c)) {
                case 1:

                    $result = $controller === $c[0] || $module === $c[0];
                    break;
                case 2:

                    $result = ($controller === $c[0] && $action === $c[1]) ||
                        ($module === $c[0] && $controller === $c[1]);
                    break;
                case 3:

                    $result = $module === $c[0] && $controller === $c[1] &&
                        $action === $c[2];
                    break;
            }
            return $result;
        };

        echo Menu::widget([
            'options' => [
                'id' => 'main-menu',
                'class' => 'gui-controls',
            ],
            'activeCssClass' => 'active',
            'activateParents' => true,
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<div class="gui-icon"><i class="md md-home"></i></div>
                       <span class="title">Рабочий стол</span>',
                    'url' => '/',
                    'active' => $isActive('dashboard'),
                ],
                [
                    'label' => '<div class="gui-icon"><i class="md md-perm-contact-cal"></i></div>
                    <span class="title">Физические лица</span>',
                    'url' => '@natural',
                    'active' => $isActive('natural'),
                ],
                [
                    'label' => '<div class="gui-icon"><i class="md md-account-balance"></i></div>
                    <span class="title">Юридические лица</span>',
                    'url' => '@legal',
                    'active' => $isActive('legal'),
                ],
                [
                    'label' => '<div class="gui-icon"><i class="md md-work"></i></div>
                    <span class="title">Сделки</span>',
                    'url' => '@leads',
                    'active' => $isActive('lead'),
                ],
                [
                    'label' => '<div class="gui-icon"><i class="md md-access-time"></i></div>
                    <span class="title">Задачи</span>',
                    'url' => '@tasks',
                    'active' => $isActive('task'),
                ],
                [
                    'label' => '<div class="gui-icon"><i class="md md-insert-drive-file"></i></div>
                    <span class="title">Отчеты</span>',
                    'options' => [
                        'class' => 'gui-folder',
                    ],
                    'url' => 'javascript:void(0);',
                    'items' => [
                        [
                            'label' => '<span class="title">Физические лица</span>',
                            'url' => '@statistic-natural',
                            'active' => $isActive('statistic/natural'),
                        ],
                        [
                            'label' => '<span class="title">Юридические лица</span>',
                            'url' => '@statistic-legal',
                            'active' => $isActive('statistic/legal'),
                        ],
                        [
                            'label' => '<span class="title">Сделки</span>',
                            'url' => '@statistic-leads',
                            'active' => $isActive('statistic/leads'),
                        ],
                        [
                            'label' => '<span class="title">Задачи</span>',
                            'url' => '@statistic-tasks',
                            'active' => $isActive('statistic/tasks'),
                        ],
                    ],
                    'visible' => Yii::$app->user->can('general'),
                ],
                [
                    'label' => '<div class="gui-icon"><i class="fa fa-users"></i></div>
                        <span class="title">Пользователи</span>',
                    'url' => '/user/admin',
                    'visible' => Yii::$app->user->identity->isAdmin,
                    'active' => $isActive('admin'),
                ]
            ],
        ]); ?>
        <!-- END MAIN MENU -->

        <div class="menubar-foot-panel">
            <small class="no-linebreak hidden-folded">

            </small>
        </div>
    </div><!--end .menubar-scroll-panel-->
</div><!--end #menubar-->
<!-- END MENUBAR -->