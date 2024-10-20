<?php

namespace app\assets;

use yii\web\AssetBundle;

class MaterialAdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/vendor/materialadmin/css/theme-default/bootstrap.css',
        'css/vendor/materialadmin/css/theme-default/materialadmin.css',
        'css/vendor/materialadmin/css/theme-default/font-awesome.min.css',
        'css/vendor/materialadmin/css/theme-default/material-design-iconic-font.min.css',
        'css/vendor/materialadmin/css/theme-default/libs/select2/select2.css_1422823373.css',
        'css/vendor/materialadmin/css/theme-default/libs/toastr/toastr.css_1422823374.css',
        'css/vendor/materialadmin/css/theme-default/libs/rickshaw/rickshaw.css',
        'css/vendor/materialadmin/css/theme-default/libs/morris/morris.core.css',
        'css/vendor/materialadmin/css/theme-default/libs/wizard/wizard.css?1422823375',
        'css/vendor/materialadmin/css/theme-default/libs/bootstrap-datepicker/datepicker3.css',
    ];

    public $js = [
        'js/vendor/materialadmin/libs/jquery/jquery-migrate-1.2.1.min.js',
        'js/vendor/materialadmin/libs/autosize/jquery.autosize.min.js',
        'js/vendor/materialadmin/libs/select2/select2.min.js',
        'js/vendor/materialadmin/libs/moment/moment.min.js',
        'js/vendor/materialadmin/core/cache/ec2c8835c9f9fbb7b8cd36464b491e73.js',
        'js/vendor/materialadmin/libs/spin.js/spin.min.js',
        'js/vendor/materialadmin/libs/jquery-knob/jquery.knob.min.js',
        'js/vendor/materialadmin/libs/sparkline/jquery.sparkline.min.js',
        'js/vendor/materialadmin/libs/nanoscroller/jquery.nanoscroller.min.js',
        'js/vendor/materialadmin/libs/toastr/toastr.js',
        'js/vendor/materialadmin/libs/wizard/jquery.bootstrap.wizard.min.js',
        'js/vendor/materialadmin/libs/bootstrap-datepicker/bootstrap-datepicker.js',
        'js/vendor/materialadmin/libs/bootstrap-datepicker/locales/bootstrap-datepicker.ru.js',
        'js/vendor/materialadmin/core/cache/43ef607ee92d94826432d1d6f09372e1.js',
        'js/vendor/materialadmin/core/cache/63d0445130d69b2868a8d28c93309746.js',
        'js/vendor/materialadmin/core/cache/63d0445130d69b2868a8d28c93309746.js',
        'js/vendor/materialadmin/core/demo/DemoFormWizard.js',
        'js/vendor/materialadmin/core/demo/Demo.js',
        //'js/vendor/materialadmin/core/demo/DemoDashboard.js',
    ];
}
