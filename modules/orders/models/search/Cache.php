<?php

namespace orders\models\search;

use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

class Cache extends OrdersSearchDecorator
{
    private string $cacheKey;
    private int $cacheExpiration;

    /**
     * Конструктор.
     * @param IOrdersSearch $component
     */
    public function __construct(IOrdersSearch $component)
    {
        $this->cacheKey = 'page:' . Url::current() . ':cache';
        $this->cacheExpiration = Yii::$app->params['cache_expiration'];

        parent::__construct($component);
    }

    /**
     * Добавление кэша для метода "getData".
     * @return ActiveDataProvider
     */
    public function getData(): ActiveDataProvider
    {
        $cache = Yii::$app->cache;

        if (($dataProvider = $cache->get($this->cacheKey)) === false) {
            // В кэше нет данных
            $dataProvider = parent::getData();

            // Заносим в кэш.
            $cache->set($this->cacheKey, $dataProvider, $this->cacheExpiration);
        }

        return $dataProvider;
    }

    /**
     * Добавление кэша для метода "getOrdersCountByServices".
     * @return array
     */
    public function getOrdersCountByServices(): array
    {
        $cache = Yii::$app->cache;

        if (($data = $cache->get($this->cacheKey)) === false) {
            // В кэше нет данных
            $data = parent::getOrdersCountByServices();

            // Заносим в кэш.
            $cache->set($this->cacheKey, $data, $this->cacheExpiration);
        }

        return $data;
    }
}
