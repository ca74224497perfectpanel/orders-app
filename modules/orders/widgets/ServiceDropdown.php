<?php

namespace orders\widgets;

use orders\models\search\Cache;
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
        $orderSearch = new Cache(new OrdersSearch());

        return $this->render('service-dropdown', [
            'countByServices' => $orderSearch->getOrdersCountByServices(),
            'service' => Yii::$app->request->get('service_id')
        ]);
    }
}
