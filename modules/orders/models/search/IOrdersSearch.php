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
