<?php

/* @var $dataProvider ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\widgets\GridControl;
use app\widgets\ModeDropdown;
use yii2tech\csvgrid\CsvGrid;
use app\widgets\ServiceDropdown;
use app\modules\orders\models\Orders;

// Описание столбцов таблицы.
$columns = [
    'id', 'user_id',
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
                $item->service_id . '</span> Likes';
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
        'format' => ['date', 'php:Y-m-d H:i:s']
    ]
];

/* Отдаем CSV-файл по запросу */
if (Yii::$app->request->get('get-csv')) {
    $exporter = new CsvGrid([
        'dataProvider' => $dataProvider,
        'columns' => $columns]
    );
    $exporter->export()->send('orders.csv');
}
?>
<div class="container-fluid">
    <!--Фильтр статуса и поиск-->
    <?= GridControl::widget(); ?>

    <!--Таблица с данными-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => Yii::t('text', '{begin} to {end} of {totalCount}'),
        'columns' => $columns,
        'tableOptions' => ['class' => 'table order-table']
     ]); ?>

    <!--Ссылка на скачивание CSV-файла заказов-->
    <?= Html::a(
        Yii::t('text', 'Save result →'),
        Url::current(['get-csv' => 'true']),
        ['target' => '_blank', 'style' => 'float: right;']
    ); ?>
</div>