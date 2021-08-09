<?php

namespace orders\models\search;

use yii\data\ActiveDataProvider;

interface IOrdersSearch
{
    /**
     * @return ActiveDataProvider
     */
    public function getData(): ActiveDataProvider;

    /**
     * @return array
     */
    public function getOrdersCountByServices(): array;
}

class OrdersSearchDecorator implements IOrdersSearch
{
    /**
     * @var IOrdersSearch
     */
    private IOrdersSearch $component;

    /**
     * @param IOrdersSearch $component
     */
    public function __construct(IOrdersSearch $component)
    {
        $this->component = $component;
    }

    /**
     * @return ActiveDataProvider
     */
    public function getData(): ActiveDataProvider
    {
        return $this->component->getData();
    }

    /**
     * @return array
     */
    public function getOrdersCountByServices(): array
    {
        return $this->component->getOrdersCountByServices();
    }
}