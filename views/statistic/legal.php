<?php

use app\models\Branch;
use app\models\Users;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LegalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчет по юридическим лицам';
$this->params['breadcrumbs'][] = $this->title;

define('_MPDF_TTFONTDATAPATH', Yii::getAlias('@runtime/mpdf'));

$columns = [
    [
        'attribute' => 'name',
        'format' => 'html',
        'label' => 'Название',
        'options' => [
            'class' => 'col-md-2',
        ],
    ],
    [
        'attribute' => 'okpo',
        'format' => 'text',
        'label' => 'ОКПО',
        'options' => [
            'class' => 'col-md-1',
        ],
    ],
    [
        'attribute' => 'status',
        'format' => 'html',
        'label' => 'Активен',
        'options' => [
            'class' => 'col-md-1',
        ],
        'value' => function ($model) {
            return $model->active ? '<span class="opened">Да</span>'
                : '<span class="closed">Нет</span>';
        },
        'filter' => Html::activeDropDownList($searchModel, 'status',
            $searchModel->statusLabels(),
            [
                'class' => 'form-control select2-list',
                'data-placeholder' => '...',
                'prompt' => '',
            ]),
    ],
    [
        'attribute' => 'description',
        'format' => 'html',
        'label' => 'Описание Клиента',
        'options' => [
            'class' => 'col-md-3',
        ],
    ],
    [
        'attribute' => 'branch',
        'format' => 'text',
        'label' => 'Отделение',
        'options' => [
            'class' => 'col-md-1',
        ],
        'value' => function ($model) {
            return $model->rUser->profile->branch->code;
        },
        'filter' => Html::activeDropDownList($searchModel, 'branch',
            ArrayHelper::map(Branch::find()->all(), 'code', 'code'),
            [
                'class' => 'form-control select2-list',
                'data-placeholder' => '...',
                'prompt' => '',

            ]),

    ],
    [
        'attribute' => 'created_at',
        'format' => 'datetime',
        'label' => 'Создан',
        'options' => [
            'class' => 'col-md-2',
        ],
        'filter' => '<div class="form-group row no-margin"><div class="col-xs-6 no-padding">' . DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'from',
                'options' => [
                    'class' => 'form-control datepicker',
                    'placeholder' => 'от',
                ],
                'clientOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'language' => 'ru',
                ]
            ]) . '</div><div class="col-xs-6 no-padding">' . DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'to',
                'options' => [
                    'class' => 'form-control datepicker',
                    'placeholder' => 'до',
                ],
                'clientOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'language' => 'ru',
                ]
            ]) . '</div></div>',
    ],
    [
        'label' => 'Обновлен',
        'format' => 'datetime',
        'attribute' => 'updated_at',
        'headerOptions' => ['style' => 'display:none;'],
        'filterOptions' => ['style' => 'display:none;'],
        'options' => ['style' => 'display:none;'],
        'contentOptions' => ['style' => 'display:none;'],
    ],
    [
        'attribute' => 'responsible',
        'format' => 'raw',
        'label' => 'Ответственный',
        'options' => [
            'class' => 'col-md-1',
        ],
        'value' => function ($model) {
            return Html::a($model->rUser->profile->name,
                ['@user-profile', 'id' => $model->rUser->id],
                ['data-pjax' => '0']
            );
        },
        'filter' => Html::activeDropDownList($searchModel, 'rid',
            ArrayHelper::map(Users::find()->joinWith(['profile' => function ($q) {
                $q->byName();
            }])->select(['id', 'profile.name'])->all(), 'id', 'profile.name'),
            [
                'class' => 'form-control select2-list',
                'data-placeholder' => '...',
                'prompt' => '',
            ]),
    ],
    [
        'attribute' => 'creator',
        'format' => 'raw',
        'label' => 'Создал',
        'options' => [
            'class' => 'col-md-1',
        ],
        'value' => function ($model) {
            return Html::a($model->cUser->profile->name,
                ['@user-profile', 'id' => $model->cUser->id],
                ['data-pjax' => '0']
            );
        },
        'filter' => Html::activeDropDownList($searchModel, 'cid',
            ArrayHelper::map(Users::find()->joinWith(['profile' => function ($q) {
                $q->byName();
            }])->select(['id', 'profile.name'])->all(), 'id', 'profile.name'),
            [
                'class' => 'form-control select2-list',
                'data-placeholder' => '...',
                'prompt' => '',
            ]),
    ],
    [
        'label' => 'Контактное лицо',
        'format' => 'html',
        'value' => function ($model) {
            return $model->extraAttributes['Контактное лицо'] ?? '';
        },
        'headerOptions' => ['style' => 'display:none;'],
        'filterOptions' => ['style' => 'display:none;'],
        'options' => ['style' => 'display:none;'],
        'contentOptions' => ['style' => 'display:none;'],
    ],
    [
        'label' => 'Телефон',
        'format' => 'html',
        'value' => function ($model) {
            return $model->extraAttributes['Телефон контактного лица'] ?? '';
        },
        'headerOptions' => ['style' => 'display:none;'],
        'filterOptions' => ['style' => 'display:none;'],
        'options' => ['style' => 'display:none;'],
        'contentOptions' => ['style' => 'display:none;'],
    ],
    [
        'label' => 'Должность',
        'format' => 'html',
        'value' => function ($model) {
            return $model->extraAttributes['Должность контактного лица'] ?? '';
        },
        'headerOptions' => ['style' => 'display:none;'],
        'filterOptions' => ['style' => 'display:none;'],
        'options' => ['style' => 'display:none;'],
        'contentOptions' => ['style' => 'display:none;'],
    ],
];

?>

<div class="contacts-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin([
        'id' => 'pjaxList',
        'options' => [
            'class' => 'pjax-list',
        ],
    ]); ?>
    <div class="row">
        <div class="col-lg-2 col-lg-offset-10">
            <div class="card">
                <div class="card-body small-padding">
                    <?php

                    $cols = [];

                    echo ExportMenu::widget(array(
                        'dataProvider' => $dataProvider,
                        'columns' => $columns,
                        'filename' => 'отчет_юр_' . date('d.m.Y_H:i:s'),
                        'target' => ExportMenu::TARGET_BLANK,
                        'showConfirmAlert' => false,
                        'fontAwesome' => true,
                        'dropdownOptions' => array(
                            'class' => 'btn btn-primary',
                            'icon' => '<i class="fa fa-share-square-o"></i>',
                        ),
                        'columnSelectorOptions' => array(
                            'class' => 'btn btn-primary',
                            'icon' => '<i class="fa fa-list-alt"></i>'
                        ),
                        'onRenderHeaderCell' => function ($cell, $content, $grid) use (&$cols) {
                            $cols[$content] = $cell->getColumn();
                        },
                        'onRenderSheet' => function ($sheet, $grid) use (&$cols) {

                            $sheet->getDefaultStyle()
                                ->getAlignment()
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                            $highestRow = $sheet->getHighestRow();

                            $sheet->getDefaultRowDimension()->setRowHeight(-1);

                            foreach ($cols as $key => $col) {
                                switch ($key) {
                                    case 'Описание Клиента':
                                        $sheet->getColumnDimension($col)->setAutoSize(false);
                                        $sheet->getColumnDimension($col)->setWidth(80);

                                        for ($row = 2; $row <= $highestRow; ++$row) {
                                            $cell = $sheet->getCell($col . $row);
                                            $cell->getStyle()
                                                ->getAlignment()
                                                ->setWrapText(true);
                                        }
                                        break;
                                }
                            }
                        }
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table table-condensed'
                        ],
                        'columns' => $columns,
                        'summary' => "Показано <b>{begin} - {end}</b> из <b>{totalCount}</b>",
                        'emptyText' => 'Подходящих записей не найдено.',
                    ]); ?>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>
