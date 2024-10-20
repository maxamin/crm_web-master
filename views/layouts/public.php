<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- BEGIN META -->
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- END META -->

    <?= Html::csrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?> | CONCORD CRM</title>

    <!-- BEGIN STYLESHEETS -->
    <?php $this->head() ?>
    <!-- END STYLESHEETS -->
</head>

<?php $this->beginBody() ?>
<body class="menubar-hoverable header-fixed">

<!-- BEGIN BASE-->
<!-- BEGIN CONTENT-->
<section class="section-account">
    <div class="img-backdrop"
         style="background-image: url('<?= \Yii::$app->request->BaseUrl ?>/img/vendor/materialadmin/img16.jpg')"></div>
    <div class="spacer"></div>
    <?= $content ?>
</section>
<!-- END CONTENT -->
<!-- END BASE -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
