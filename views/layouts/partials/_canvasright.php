<!-- BEGIN OFFCANVAS RIGHT -->
<div class="offcanvas">


    <!-- BEGIN OFFCANVAS SEARCH -->
    <div id="offcanvas-search" class="offcanvas-pane width-8">
        <div class="offcanvas-head">
            <header class="text-primary">Search</header>
            <div class="offcanvas-tools">
                <a class="btn btn-icon-toggle btn-default-light pull-right" data-dismiss="offcanvas">
                    <i class="md md-close"></i>
                </a>
            </div>
        </div>

        <div class="offcanvas-body no-padding">
            <ul class="list ">
                <?php
                $curFirstLetter = "";
                foreach (app\models\Users::find()->joinWith(['profile' => function ($q) { $q->orderBy('name'); }])->all() as $key => $val):
                    $firstLetter = mb_substr($val->profile->name, 0, 1, 'utf-8');
                    if ($firstLetter != $curFirstLetter):
                        ?>
                        <li class="tile divider-full-bleed">
                            <div class="tile-content">
                                <div class="tile-text"><strong><?php echo $firstLetter; ?></strong></div>
                            </div>
                        </li>
                    <?php endif; ?>
                    <li class="tile">
                        <a class="tile-content ink-reaction open-chat" href="#offcanvas-chat"
                           data-toggle="offcanvas" data-backdrop="false" data-id='<?= $val->id; ?>'
                           class='open-chat'>
                            <div class="tile-icon">
                                <img src="<?php
                                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $val->getExtProfile()->extFields['photo_url'])) {
                                    echo $val->getExtProfile()->extFields['photo_url'];
                                } else {
                                    echo \Yii::$app->params['defaultUsersPhoto'];
                                }
                                ?>" alt=""/>
                            </div>
                            <div class="tile-text">
                                <?php echo $val->profile->name; ?>
                                <small>123-123-3210</small>
                            </div>
                        </a>
                    </li>
                    <?php
                    $curFirstLetter = $firstLetter;
                endforeach;
                ?>
            </ul>
        </div><!--end .offcanvas-body -->
    </div><!--end .offcanvas-pane -->
    <!-- END OFFCANVAS SEARCH -->


    <!-- BEGIN OFFCANVAS CHAT -->
    <div id="offcanvas-chat" class="offcanvas-pane style-default-light width-12">
        <div class='chats'>
        </div>
    </div><!--end .offcanvas-pane -->
    <!-- END OFFCANVAS CHAT -->

</div><!--end .offcanvas-->
<!-- END OFFCANVAS RIGHT -->