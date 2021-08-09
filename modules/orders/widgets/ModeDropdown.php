<?php

namespace orders\widgets;

use orders\models\Orders;
use Yii;
use yii\base\Widget;

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