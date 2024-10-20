<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 */
?>

<?php $this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]) ?>

<div class="card-body">
    <ul class="list divider-full-bleed">
        <li class="tile">
            <div class="tile-content no-padding">
                <div class="tile-text">
                    <small>Последняя авторизация</small>
                    <?php if (isset($user->last_login_at)) {
                        echo Yii::$app->formatter->asDatetime($user->last_login_at);
                    } else {
                        echo 'Никогда';
                    } ?>
                </div>
            </div>
        </li>
        <li class="tile">
            <div class="tile-content no-padding">
                <div class="tile-text">
                    <small><?= Yii::t('user', 'Registration time') ?></small>
                    <?= Yii::$app->formatter->asDatetime($user->created_at) ?>
                </div>
            </div>
        </li>
        <?php if (isset($user->registration_ip)): ?>
            <li class="tile">
                <div class="tile-content no-padding">
                    <div class="tile-text">
                        <small>Регистрационный IP</small>
                        <?= $user->registration_ip ?>
                    </div>
                </div>
            </li>
        <?php endif; ?>
        <li class="tile">
            <div class="tile-content no-padding">
                <div class="tile-text">
                    <small>Активация</small>
                    <?php if ($user->isConfirmed): ?>
                        <span class="text-success">Активирован
                            <?= Yii::$app->formatter->asDatetime($user->confirmed_at); ?>
                        </span>
                    <?php else: ?>
                        <span class="text-danger">Аккаунт не активирован</span>
                    <?php endif ?>
                </div>
            </div>
        </li>
        <li class="tile">
            <div class="tile-content no-padding">
                <div class="tile-text">
                    <small>Подтверждение</small>
                    <?php if ($user->isVerified): ?>
                        <span class="text-success">Подтвержден
                            <?= Yii::$app->formatter->asDatetime($user->verified_at); ?>
                        </span>
                    <?php else: ?>
                        <span class="text-danger"><?= Yii::t('user', 'Unconfirmed') ?></span>
                    <?php endif ?>
                </div>
            </div>
        </li>
        <li class="tile">
            <div class="tile-content no-padding">
                <div class="tile-text">
                    <small>Блокировка</small>
                    <?php if ($user->isBlocked): ?>
                        <span class="text-danger">Заблокирован
                            <?= Yii::$app->formatter->asDatetime($user->blocked_at); ?>
                        </span>
                    <?php else: ?>
                        <span class="text-success"><?= Yii::t('user', 'Not blocked') ?></span>
                    <?php endif ?>
                </div>
            </div>
        </li>
    </ul>
</div><!--end .card-body.tab-content -->
<!-- END FORM TAB PANES -->
<!-- BEGIN FORM FOOTER -->
<div class="card-actionbar">
    <div class="card-actionbar-row">
    </div><!--end .card-actionbar-row -->
</div><!--end .card-actionbar -->

<?php $this->endContent() ?>
