<?php

use app\widgets\assignments\AssignmentsWidget;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 */
?>

<?php $this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]) ?>

<?= AssignmentsWidget::widget(['userId' => $user->id]) ?>

<?php $this->endContent() ?>
