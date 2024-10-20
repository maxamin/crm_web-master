<?php

namespace app\assets;

use yii\web\AssetBundle;


class GoogleFontsAsset extends AssetBundle
{
    public $baseUrl = 'http://fonts.googleapis.com';

    public $css = [
        'css?family=Roboto:300italic,400italic,300,400,500,700,900',
    ];
}
