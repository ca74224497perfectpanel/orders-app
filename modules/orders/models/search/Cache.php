<?php

namespace orders\models\search;

use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

class Cache extends OrdersSearchDecorator
{
    /**
     * Добавление кэша для метода "getData".
     * @return ActiveDataProvider
     */
    public function getData(): ActiveDataProvider
    {
        $currentUrl = Url::current();
        $key = "page:$currentUrl:cache";
        $cache = Yii::$app->cache;
        $expiration = Yii::$app->params['cache_expiration'];

        if (($dataProvider = $cache->get($key)) === false) {
            // В кэше нет данных
            $dataProvider = parent::getData();

            // Заносим в кэш.
            $cache->set($key, $dataProvider, $expiration);
        }

        return $dataProvider;
    }

    /**
     * Добавление кэша для метода "getOrdersCountByServices".
     * @return array
     */
    public function getOrdersCountByServices(): array
    {
        $key = 'services-stat';
        $cache = Yii::$app->cache;
        $expiration = Yii::$app->params['cache_expiration'];

        if (($data = $cache->get($key)) === false) {
            // В кэше нет данных
            $data = parent::getOrdersCountByServices();

            // Заносим в кэш.
            $cache->set($key, $data, $expiration);
        }

        return $data;
    }
}