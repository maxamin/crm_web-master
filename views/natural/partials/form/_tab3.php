<?php

use yii\helpers\Html;
use yii\helpers\Url;

use app\models\ContactsLink;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ContactForm */
/* @var $form yii\widgets\ActiveForm */

$contactsLink = new ContactsLink(); //for empty template
$contactsLink->loadDefaultValues();
$keep = [];

?>

<div class="tab-pane" id="tab3">
    <!-- hidden template for add button -->
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body no-padding">
                    <ul class="list divider-full-bleed">
                        <div id="contactsLinkContainer">
                            <?php foreach ($model->contactsLink as $key => $link): ?>
                                <?php

                                $keep[] = $link->link->id;

                                if ($link->isNewRecord) {
                                    $key = strpos($key, 'new') !== false ? $key : 'new' . $key;
                                } else {
                                    $key = $link->id;
                                }
                                ?>
                                <?= $this->render('_contact-link-li', [
                                    'form' => $form,
                                    'link' => $link,
                                    'key' => $key,
                                    'id' => $link->link->id,
                                    'name' => $link->link->name,
                                    'okpo' => $link->link->okpo,
                                ]); ?>
                            <?php endforeach; ?>

                            <!-- hidden template for add button -->
                            <div id="contactsLinkTemplate" hidden="hidden">
                                <?= $this->render('_contact-link-li', [
                                    'form' => $form,
                                    'link' => $contactsLink,
                                    'key' => 'temp_key',
                                    'id' => 'temp_id',
                                    'name' => 'temp_name',
                                    'okpo' => 'temp_okpo',
                                ]); ?>
                            </div>

                            <span id="contactsLinkKeep" data-keep="<?= json_encode($keep) ?>" hidden="hidden"></span>
                            <span id="contactsLinkKey" data-key="<?= isset($key) ? str_replace('new', '', $key) : 0 ?>" hidden="hidden"></span>
                        </div>
                    </ul>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>

    <em>Если информация о юридическом лице отсутствует - не заполняйте данную форму</em>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <?= Html::textInput('search_contacts', null, [
                    'id' => 'searchContacts',
                    'class' => 'form-control',
                    'data-url' => Url::to(['@legal']),
                ]); ?>
                <?= Html::label('Поиск юридического лица', 'search_contacts'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div id="searchContactsContainer" style="display: none;">
                <div class="card card-bordered style-primary">
                    <div class="card-head">
                        <header><i class="fa fa-fw fa-tag"></i> Похожие юридические лица</header>
                        <div class="tools">
                            <a class="btn btn-icon-toggle hide-btn" data-container-selector="#searchContactsContainer"><i class="md md-close"></i></a>
                        </div>
                    </div>
                    <div class="card-body height-8 scroll style-default-bright">
                        <div id="searchContactsResult"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--end #tab3 -->

<script id="searchContactsTmpl" type="text/x-handlebars-template">
    {{#each contacts}}
        <span style="font-size: 18px; font-weight: bold">{{name}}</span><br>
        <span><b>ОКПО: </b>{{okpo}}</span>
        <div class="row text-center" style="padding-top: 20px; border-bottom: 1px solid #ccc">
            <div class="col-md-12" style="padding-bottom: 20px">
                <a href="javascript:void(0);" class="btn btn-accent-light link-contact" data-id="{{id}}"
                   data-name="{{name}}" data-okpo="{{okpo}}">Связать</a>
            </div>
            <div class="col-md-12" style="padding-bottom: 20px">
                <?= Html::a('Перейти в карточку', Url::to(['@legal-view']).'/{{id}}', ['class' => 'btn btn-primary'])  ?>
            </div>
        </div>
    {{/each}}
</script>