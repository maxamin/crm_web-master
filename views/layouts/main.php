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


<?php echo $this->render('partials/_header'); ?>

<!-- BEGIN BASE-->
<div id="base">
    <!-- BEGIN OFFCANVAS LEFT -->
    <div class="offcanvas">
    </div><!--end .offcanvas-->
    <!-- END OFFCANVAS LEFT -->

    <!-- BEGIN CONTENT-->
    <div id="content">
        <section>
            <div class="section-body">
                <?= $this->render('partials/_flash') ?>
                <?= $content ?>
            </div>
        </section>
    </div><!--end #content-->
    <!-- END CONTENT -->

    <?php echo $this->render('partials/_menubar'); ?>

    <?php //echo $this->render('partials/_canvasright'); ?>

</div><!--end #base-->
<!-- END BASE -->

<?php echo $this->render('partials/_modals'); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
