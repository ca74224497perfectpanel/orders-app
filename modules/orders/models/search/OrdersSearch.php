<?php

namespace orders\models\search;

use Yii;
use yii\db\Query;
use yii\db\Expression;
use orders\models\Orders;
use orders\models\queries\OrdersQuery;

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
                    $query->andWhere(['like', 'link', $search]);
                    break;
                case Orders::SEARCH_TYPE_USER_NAME:
                    $query->joinWith('user')->andWhere(
                        ['like', "CONCAT(first_name, ' ', last_name)", $search]
                    );
                    break;
            }
        }

        /**
         * Сортировка заказов по "id" в обратном порядке.
         */
        $query->orderBy(['id' => SORT_DESC]);

        return $query;
    }

    /**
     * Получение количества заказов по сервисам + общее количество заказов.
     * @return array
     */
    public static function getOrdersCountByServices(): array
    {
        $key = 'services-stat';
        $cache = Yii::$app->cache;
        $expiration = Yii::$app->params['cache_expiration'];

        // Получаем данные о статистике по сервисам из кэша.
        $data = $cache->get($key);

        if ($data === false /* в кэше нет данных */) {
            // Запрашиваем данные из БД.
            $byServicesQuery = (new Query())
                ->select(['service_id AS id', 'COUNT(*) AS count'])
                ->from('orders')
                ->groupBy(['service_id']);

            $totalQuery = (new Query())
                ->select([new Expression(0), 'COUNT(*)'])
                ->from('orders');

            $byServicesQuery->union($totalQuery);

            $data = (new Query())
                ->select(['t1.id', 't1.count', 't2.name'])
                ->from(['t1' => $byServicesQuery])
                ->leftJoin('services AS t2', 't1.id = t2.id')
                ->orderBy('t1.count DESC')
                ->all();

            // Заносим данные в кэш.
            $cache->set($key, $data, $expiration);
        }

        return empty($data) ? [] : $data;
    }
}
