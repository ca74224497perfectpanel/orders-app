<?php

namespace app\modules\orders\models\search;

use app\modules\orders\models\Orders;
use app\modules\orders\models\queries\OrdersQuery;

class OrdersSearch
{
    /**
     * @var Orders
     */
    private Orders $model;

    /**
     * Конструктор класса.
     * @param array $queryParams
     */
    public function __construct(array $queryParams = [])
    {
        $this->model = new Orders();
        $this->model->scenario = Orders::SCENARIO_SEARCH;
        $this->model->load($queryParams, '');

        if (!$this->model->validate()) {
            // Валидация провалилась, сбрасываем значения полей.
            $this->model = new Orders();
        }
    }

    /**
     * Возвращает сформированный запрос.
     * @return OrdersQuery
     */
    public function getQuery(): OrdersQuery
    {
        $query = $this->model::find();

        /**
         * Фильтрация по статусу заказа.
         */
        if (!is_null($status = $this->model->attributes['status'])) {
            $query->andWhere(['status' => $status]);
        }

        /**
         * Фильтрация по режиму.
         */
        if (!is_null($mode = $this->model->attributes['mode']) &&
            (int)$mode !== Orders::MODE_ALL) {
            $query->andWhere(['mode' => $mode]);
        }

        /**
         * Фильтрация по сервису.
         */
        if (!is_null($service = $this->model->attributes['service_id'])) {
            $query->andWhere(['service_id' => $service]);
        }

        /**
         * Поиск.
         */
        if (!is_null($search = $this->model->attributes['search'])) {
            switch ($this->model->attributes['search_type']) {
                case Orders::SEARCH_TYPE_ORDER_ID:
                    $query->andWhere(['id' => $search]);
                    break;
                case Orders::SEARCH_TYPE_LINK:
                    $search->andWhere(['like', 'link', $search]);
                    break;
                case Orders::SEARCH_TYPE_USER_NAME:
                    $query
                        ->joinWith('user')
                        ->andWhere([
                            'like',
                            "CONCAT(first_name, ' ', last_name)",
                            $search
                        ]);
                    break;
            }
        }

        /**
         * Сортировка заказов по "id" в обратном порядке.
         */
        $query->orderBy(['id' => SORT_DESC]);

        return $query;
    }
}