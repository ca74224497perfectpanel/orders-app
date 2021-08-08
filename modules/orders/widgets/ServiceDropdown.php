<?php

namespace app\modules\orders\widgets;

use Yii;
use yii\base\Widget;
use app\modules\orders\models\Orders;

class ServiceDropdown extends Widget
{
    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {
        return $this->render('service-dropdown', [
            'countByServices' => Orders::getOrdersCountByServices(),
            'service' => Yii::$app->request->get('service_id')
        ]);
    }
}