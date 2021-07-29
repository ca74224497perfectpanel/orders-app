<?php

namespace app\modules\orders;

use Yii;

/**
 * orders module definition class
 */
class OrdersModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\orders\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // У модуля свой собственный лэйоут.
        $this->layout = 'orders-layout';
    }
}
