<?php

use app\modules\orders\models\Orders;
use app\widgets\GridControl;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$dataProvider = new ActiveDataProvider([
    'query' => Orders::find(),
    'pagination' => [
        'pageSize' => 100
    ]
]);
?>
<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Orders</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <!--Фильтр статуса и поиск-->
    <?= GridControl::widget(); ?>

    <!--Таблица с данными-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '{begin} to {end} of {totalCount}',
        'columns' => [
            'id',
            'user_id',
            'link',
            'quantity',
            'service_id',
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
        ]
     ]); ?>
</div>