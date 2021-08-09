<?php

namespace orders\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use orders\models\Orders;

class GridControl extends Widget
{
    public array $orderStatuses;

    public function init(): void
    {
        parent::init();

        if (empty($this->orderStatuses)) {
            $this->orderStatuses = Orders::getOrderStatuses();
        }
    }

    public function run(): string
    {
        return $this->render('grid-control', [
            'orderStatuses' => $this->orderStatuses,
            'searchTypes' => Orders::getSearchTypes(),
            'status' => Yii::$app->request->get('status'),
            'search' => Yii::$app->request->get('search'),
            'srtype' => Yii::$app->request->get('search_type'),
            'domain' => Url::canonical()
        ]);
    }
}
