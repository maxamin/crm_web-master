<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/app.css',
    ];

    public $js = [
        'js/components/app/helpers.js',
        'js/components/app/app.js',
        'js/components/app/url.js',
        'js/components/app/search-list.js',
        'js/components/app/dashboard.js',
        'js/components/app/form/form.js',
        'js/components/app/widget/widget.js',
        'js/components/app/model/model.js',
        'js/components/app/model/contact.js',
        'js/components/app/model/lead.js',
        'js/components/app/model/task.js',
        'js/components/app/model/profile.js',
    ];

    public $depends = [
        //'app\assets\GoogleFontsAsset',
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\assets\MaterialAdminAsset',
        'app\assets\BowerAsset',
        'app\assets\IE9Asset',
    ];
}
