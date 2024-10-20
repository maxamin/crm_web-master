<?php

namespace app\assets;

use yii\web\AssetBundle;

class IE9Asset extends AssetBundle
{
    public $jsOptions = ['condition' => 'lt IE 9', 'position' => \yii\web\View::POS_HEAD];

    public $js = [
        'http://www.codecovers.eu/assets/js/modules/materialadmin/libs/utils/html5shiv.js?1422823601',
        'http://www.codecovers.eu/assets/js/modules/materialadmin/libs/utils/respond.min.js?1422823601',
    ];
}
