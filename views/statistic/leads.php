<?php

use app\models\Branch;
use app\models\Contacts;
use app\models\LeadStatuses;
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

$this->title = 'Отчет по сделкам';
$this->params['breadcrumbs'][] = $this->title;

define('_MPDF_TTFONTDATAPATH', Yii::getAlias('@runtime/mpdf'));

switch ($searchModel->contype) {
    case Contacts::TYPE_LEGAL:
        $okpoLabel = 'ОКПО';
        break;
    case Contacts::TYPE_NATURAL:
        $okpoLabel = 'ИНН';
        break;
    default:
        $okpoLabel = 'ОКПО/ИНН';
};

$columns = [
    [
        'attribute' => 'contype',
        'label' => 'Тип Клиента',
        'format' => 'html',
        'options' => [
            'class' => 'col-md-1',
        ],
        'value' => function ($model) {
            return '<div class="related-list-block"><small>' . $model->contacts->getType() . '</small></div>';
        },
        'filter' => Html::activeDropDownList($searchModel, 'contype',
            Contacts::types(),
            [
                'class' => 'form-control select2-list',
                'data-placeholder' => '...',
                'prompt' => '',
            ]
        ),
    ],
    [
        'attribute' => 'contact',
        'label' => 'Название Клиента',
        'format' => 'raw',
        'options' => [
            'class' => 'col-md-2',
        ],
        'value' => function ($model) {

            return Html::a($model->contacts->getIdentifier(),
                ['@' . $model->contacts->getTypeLabel() . '-view', 'id' => $model->contacts->id],
                ['data-pjax' => '0']);
        },
    ],
    [
        'label' => $okpoLabel,
        'format' => 'text',
        'value' => function ($model) {
            return $model->contacts->okpo;
        },
        'headerOptions' => ['style' => 'display:none;'],
        'filterOptions' => ['style' => 'display:none;'],
        'options' => ['style' => 'display:none;'],
        'contentOptions' => ['style' => 'display:none;'],
    ],
    [
        'label' => 'Описание Клиента',
        'format' => 'html',
        'value' => function ($model) {
            return $model->contacts->description;
        },
        'headerOptions' => ['style' => 'display:none;'],
        'filterOptions' => ['style' => 'display:none;'],
        'options' => ['style' => 'display:none;'],
        'contentOptions' => ['style' => 'display:none;'],
    ],
    [
        'attribute' => 'product',
        'format' => 'html',
        'label' => 'Продукты',
        'options' => [
            'class' => 'col-md-2',
        ],
        'value' => function ($model) {
            return $model->getIdentifier();
        }
    ],
    [
        'attribute' => 'comment',
        'format' => 'html',
        'label' => 'Описание Сделки',
        'options' => [
            'class' => 'col-md-2',
        ],
    ],
    [
        'attribute' => 'sum',
        'format' => 'text',
        'label' => 'Сумма',
    ],
    [
        'attribute' => 'state',
        'label' => 'Статус',
        'format' => 'html',
        'options' => [
            'class' => 'col-md-1',
        ],
        'value' => function ($model) {
            return $model->leadStatus->name;
        },
        'filter' => Html::activeDropDownList($searchModel, 'stid',
            ArrayHelper::map(LeadStatuses::find()->ordered()->all(), 'id', 'name'),
            [
                'class' => 'form-control select2-list',
                'data-placeholder' => '...',
                'prompt' => '',
            ]),
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
        'label' => 'Создана',
        'options' => [
            'class' => 'col-md-1',
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
        'label' => 'Обновлена',
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

                    $selectedCols = array_keys($columns);

                    if (!empty($searchModel->contype)) {

                        $key = array_search('contype', array_column($columns, 'attribute'));
                        $key = array_search($key, $selectedCols);
                        unset($selectedCols[$key]);
                    }

                    $excelCols = []; //Для форматирования в excel

                    echo ExportMenu::widget(array(
                        'dataProvider' => $dataProvider,
                        'columns' => $columns,
                        'filename' => 'отчет_сделки_' . date('d.m.Y_H:i:s'),
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
                        'selectedColumns' => $selectedCols,
                        'onRenderHeaderCell' => function ($cell, $content, $grid) use (&$excelCols) {
                            $excelCols[$content] = $cell->getColumn();
                        },
                        'onRenderSheet' => function ($sheet, $grid) use (&$excelCols) {

                            $sheet->getDefaultStyle()
                                ->getAlignment()
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                            $highestRow = $sheet->getHighestRow();

                            $sheet->getDefaultRowDimension()->setRowHeight(-1);

                            foreach ($excelCols as $key => $col) {
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
                                    case 'Описание Сделки':
                                        $sheet->getColumnDimension($col)->setAutoSize(false);
                                        $sheet->getColumnDimension($col)->setWidth(80);

                                        for ($row = 2; $row <= $highestRow; ++$row) {
                                            $cell = $sheet->getCell($col . $row);
                                            $cell->getStyle()
                                                ->getAlignment()
                                                ->setWrapText(true);
                                        }
                                        break;
                                    case 'Продукты':
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
