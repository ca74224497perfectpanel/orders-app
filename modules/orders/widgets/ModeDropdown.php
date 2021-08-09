<?php

namespace orders\widgets;

use Yii;
use yii\base\Widget;
use orders\models\Orders;

class ModeDropdown extends Widget
{
    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {
        return $this->render('mode-dropdown', [
            'orderModes' => Orders::getOrderModes(),
            'mode' => Yii::$app->request->get('mode')
        ]);
    }
}