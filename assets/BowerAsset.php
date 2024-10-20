<?php

namespace app\assets;

use yii\web\AssetBundle;

class BowerAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $css = [
        'jcrop/css/Jcrop.min.css',
        'jquery-selectric/public/selectric.css',
    ];

    public $js = [
        'handlebars/handlebars.js',
        'jcrop/js/Jcrop.min.js',
        'blueimp-load-image/js/load-image.js',
        'jquery-selectric/public/jquery.selectric.js',
        'jquery.inputmask/dist/jquery.inputmask.bundle.js',
        'jquery.inputmask/extra/phone-codes/phone.js',
    ];
}
