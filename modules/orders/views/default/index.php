<?php

/* @var $dataProvider ActiveDataProvider */

use yii\grid\GridView;
use app\widgets\GridControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii2tech\csvgrid\CsvGrid;
use app\modules\orders\models\Orders;

// Описание столбцов таблицы.
$columns = [
    'id', 'user_id', 'link', 'quantity', 'service_id',
    [
        'attribute' => 'status',
        'value' => function ($item) {
            return Orders::ORDER_STATUSES[$item->status];
        }
    ],
    [
        'attribute' => 'mode',
        'value' => function ($item) {
            return Orders::MODE_STATUSES[$item->mode];
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
        'summary' => '{begin} to {end} of {totalCount}',
        'columns' => $columns
     ]); ?>

    <!--Ссылка на скачивание CSV-файла заказов-->
    <?= Html::a(
        'Save result →',
        Url::current(['get-csv' => 'true']),
        ['target' => '_blank', 'style' => 'float: right;']
    ); ?>
</div>