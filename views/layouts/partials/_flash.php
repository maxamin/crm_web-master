<?php

use yii\bootstrap\Alert;

?>

<?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert alert-dismissible alert-callout alert-' . $type],
            'body' => $message,
            'closeButton' => [
                'label' => '<i aria-hidden="true" class="md md-highlight-remove"></i>',
            ]
        ]) ?>
<?php endforeach ?>
