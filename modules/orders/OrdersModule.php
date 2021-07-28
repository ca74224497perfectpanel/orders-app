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

        // Переводы.
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/users/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath'       => '@app/modules/users/messages'
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/orders/' . $category, $message, $params, $language);
    }
}
