<?php

namespace orders;

use yii\base\Module;

/**
 * orders module definition class
 */
class OrdersModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'orders\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // У модуля свой собственный лэйаут.
        $this->layout = 'orders-layout';
    }
}
