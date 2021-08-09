<?php

namespace orders\widgets;

use Yii;
use yii\base\Widget;
use orders\models\search\OrdersSearch;

class ServiceDropdown extends Widget
{
    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {
        return $this->render('service-dropdown', [
            'countByServices' => OrdersSearch::getOrdersCountByServices(),
            'service' => Yii::$app->request->get('service_id')
        ]);
    }
}