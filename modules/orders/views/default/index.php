<?php
/** @noinspection PhpUnhandledExceptionInspection */

/**
 * @var ActiveDataProvider $dataProvider
 * @var array $columns
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use orders\widgets\GridControl;
use yii\data\ActiveDataProvider;

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
