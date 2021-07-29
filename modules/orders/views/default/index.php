<?php

use app\modules\orders\models\Orders;
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
    <ul class="nav nav-tabs p-b">
        <li class="active"><a href="#">All orders</a></li>
        <li><a href="#">Pending</a></li>
        <li><a href="#">In progress</a></li>
        <li><a href="#">Completed</a></li>
        <li><a href="#">Canceled</a></li>
        <li><a href="#">Error</a></li>
        <li class="pull-right custom-search">
            <form class="form-inline" action="/admin/orders" method="get">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" value="" placeholder="Search orders">
                    <span class="input-group-btn search-select-wrap">
                        <select class="form-control search-select" name="search-type">
                            <option value="1" selected="">Order ID</option>
                            <option value="2">Link</option>
                            <option value="3">Username</option>
                        </select>
                        <button type="submit" class="btn btn-default">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </button>
                    </span>
                </div>
            </form>
        </li>
    </ul>
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