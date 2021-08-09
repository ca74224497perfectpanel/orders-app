<?php
/** @noinspection PhpUnhandledExceptionInspection */

/* @var $dataProvider ActiveDataProvider */

use orders\widgets\ModeDropdown;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ActiveDataProvider;
use orders\models\Orders;
use orders\widgets\GridControl;
use orders\widgets\ServiceDropdown;

// Описание столбцов таблицы.
$columns = [
    'id',
    [
        'attribute' => 'user_id',
        'value' => function ($item) {
            return "{$item->user->first_name} {$item->user->last_name}";
        }
    ],
    [
        'attribute' => 'link',
        'contentOptions' => ['class' => 'link']
    ],
    'quantity',
    [
        'header' => ServiceDropdown::widget(),
        'attribute' => 'service_id',
        'contentOptions' => ['class' => 'service'],
        'headerOptions' => ['class' => 'dropdown-th'],
        'format' => 'html',
        'value' => function ($item) {
            return '<span class="label-id">' .
                $item->service->id . '</span> ' . $item->service->name;
        }
    ],
    [
        'attribute' => 'status',
        'value' => function ($item) {
            return Orders::getOrderStatuses()[$item->status];
        }
    ],
    [
        'header' => ModeDropdown::widget(),
        'attribute' => 'mode',
        'headerOptions' => ['class' => 'dropdown-th'],
        'value' => function ($item) {
            return Orders::getOrderModes()[$item->mode];
        }
    ],
    [
        'attribute' => 'created_at',
        'format' => 'html',
        'value' => function ($item) {
            return '<span class="nowrap">' . date(
                    'Y-m-d',
                    $item->created_at
                ) . '</span>' .
                '<span class="nowrap">' . date(
                    'H:i:s',
                    $item->created_at
                ) . '</span>';
        }
    ]
];

/* Отдаем CSV-файл по запросу */
if (Yii::$app->request->get('get-csv')) {
    // Редактируем колонки для репрезентации в csv-формате.

    $columns[2] = ['attribute' => 'link'];
    $columns[4] = [
        'attribute' => 'service_id',
        'value' => function ($item) {
            return "{$item->service->name} ({$item->service->id})";
        }
    ];

    unset($columns[6]['header']);
    unset($columns[6]['headerOptions']);

    $columns[7] = [
        'attribute' => 'created_at',
        'value' => function ($item) {
            return date('Y-m-d', $item->created_at) . PHP_EOL .
                date('H:i:s', $item->created_at);
        }
    ];

    $exporter = new CsvGrid([
                                'dataProvider' => $dataProvider,
                                'columns' => $columns
                            ]);
    $exporter->export()->send('orders.csv');
}
?>
<div class="container-fluid">
    <!--Фильтр статуса и поиск-->
    <?= GridControl::widget(); ?>

    <!--Таблица с данными-->
    <?= GridView::widget([
                             'dataProvider' => $dataProvider,
                             'summary' => Yii::t('text', 'orders.grid.summary'),
                             'summaryOptions' => ['class' => 'table-summary'],
                             'columns' => $columns,
                             'tableOptions' => ['class' => 'table order-table'],
                             'layout' => '{items}{pager}{summary}'
                         ]); ?>

    <!--Ссылка на скачивание CSV-файла заказов-->
    <div class="csv-download">
        <?= Html::a(
            Yii::t('text', 'orders.link.csv'),
            Url::current(['get-csv' => 'true']),
            ['target' => '_blank']
        ); ?>
    </div>
</div>
