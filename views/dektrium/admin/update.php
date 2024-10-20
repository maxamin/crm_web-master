<?php

/**
 * @var \yii\web\View $this
 * @var \dektrium\user\models\User $user
 * @var string $content
 */

use app\widgets\avatar\AvatarWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

$this->title = Yii::t('user', 'Update user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="section-body contain-lg">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body style-primary form-inverse">
                    <div class="row">
                        <div class="col-xs-12 col-lg-8 hbox-xs">
                            <div class="hbox-column width-4">
                                <?= AvatarWidget::widget([
                                    'profile' => $user->profile,
                                    'action' => Url::to(['@admin-upload-avatar', 'id' => $user->id]),
                                ]) ?>
                            </div><!--end .hbox-column -->

                            <div class="hbox-column v-top">
                                <div class="clearfix">
                                    <div class="col-lg-12">
                                        <h3><?= $user->profile->name ?></h3>
                                    </div>
                                </div>
                                <div class="clearfix opacity-75 text-lg">
                                    <div class="col-lg-6">
                                        <i class="fa fa-user"></i> &nbsp;<?= $user->username ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <i class="fa fa-envelope"></i> &nbsp;<?= $user->email ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <i class="fa fa-map-marker"></i> &nbsp;<?= $user->profile->branch->name ?> (<?= $user->profile->branch->code ?>)
                                    </div>
                                    <div class="col-lg-6">
                                        <i class="fa fa-angle-double-up"></i>
                                        <?php

                                        $roles = [];

                                        foreach (Yii::$app->authManager->getRolesByUser($user->id) as $role) {
                                            $roles[] = $role->description;
                                        }

                                        echo implode(', ', $roles);
                                        ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <i class="fa fa-clock-o"></i> &nbsp;Присоеденился <?= Yii::$app->formatter->asDate($user->created_at) ?>
                                    </div>
                                </div>
                            </div><!--end .hbox-column -->
                        </div><!--end .hbox-xs -->
                    </div><!--end .row -->
                </div><!--end .card-body -->
                <!-- END DEFAULT FORM ITEMS -->

                <div class="card-actionbar style-primary">
                    <div class="card-actionbar-row">
                        <?php if (!$user->isConfirmed) {
                            echo Html::a('<i class="fa fa-check"></i> Активировать', ['/user/admin/confirm', 'id' => $user->id], [
                                'class' => 'btn btn-primary btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                            ]);
                        } ?>
                        <?php if (!$user->isVerified) {
                            echo Html::a('<i class="fa fa-check"></i> Подтвердить', ['/user/admin/verify', 'id' => $user->id], [
                                'class' => 'btn btn-primary btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => 'Вы уверены, что хотите подтвердить данного пользователя?',
                            ]);
                        } ?>
                        <?php if ($user->isBlocked) {
                            echo Html::a('<i class="fa fa-unlock"></i> Разблокировать', ['/user/admin/block', 'id' => $user->id], [
                                'class' => 'btn btn-sm btn-primary',
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                            ]);
                        } else {
                            echo Html::a('<i class="fa fa-lock text-danger"></i> Блокировать', ['/user/admin/block', 'id' => $user->id], [
                                'class' => 'btn btn-sm btn-primary',
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                            ]);
                        } ?>
                        <?= Html::a('<i class="fa fa-trash text-danger"></i> Удалить', ['/user/admin/delete', 'id' => $user->id], [
                            'class' => 'btn btn-sm btn-primary',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('user', 'Are you sure you want to delete this user?'),
                        ]) ?>
                    </div><!--end .card-actionbar-row -->
                </div><!--end .card-actionbar -->

                <!-- BEGIN FORM TABS -->
                <div class="card-head style-primary">
                    <?= Menu::widget([
                        'options' => [
                            'class' => 'nav nav-tabs tabs-text-contrast tabs-accent',
                        ],
                        'items' => [
                            [
                                'label' => Yii::t('user', 'Profile details'),
                                'url' => ['/user/admin/update-profile', 'id' => $user->id]
                            ],
                            [
                                'label' => Yii::t('user', 'Account details'),
                                'url' => ['/user/admin/update', 'id' => $user->id]
                            ],
                            [
                                'label' => Yii::t('user', 'Information'),
                                'url' => ['/user/admin/info', 'id' => $user->id]],
                            [
                                'label' => 'Роли',
                                'url' => ['/user/admin/assignments', 'id' => $user->id],
                                'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
                            ],
                        ],
                    ]) ?>
                </div><!--end .card-head -->
                <!-- END FORM TABS -->
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
