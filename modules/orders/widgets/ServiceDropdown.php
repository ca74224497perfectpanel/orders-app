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
        $orderSearch = new OrdersSearch(Yii::$app->request->get());

        return $this->render('service-dropdown', [
            'countByServices' => $orderSearch->getOrdersCountByServices(),
            'service' => Yii::$app->request->get('service_id')
        ]);
    }
}
