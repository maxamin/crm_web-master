<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ContactForm */
/* @var $form yii\widgets\ActiveForm */

?>

<li id="link-li-<?= $key ?>" class="tile">
    <a class="tile-content ink-reaction" href="<?= Url::to('@legal-view').'/'.$id ?>">
        <div class="tile-text">
            <?= $name ?>
            <small>ОКПО: <?= $okpo ?></small>
        </div>
    </a>
    <a class="btn btn-flat ink-reaction remove-btn remove-keep-link" data-container-selector="#link-li-<?= $key ?>" data-id="<?= $id ?>">
        <i class="md md-highlight-remove"></i>
    </a>
    <?php

    $field = $form->field($link, '['.$key.']link_id')->hiddenInput(['value' => isset($link->link->id) ? $link->link->id : $id])->label(false);
    $field->enableClientValidation = false;

    echo $field;

    ?>
</li>
