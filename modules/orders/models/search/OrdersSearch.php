<?php

namespace orders\models\search;

use Yii;
use yii\db\Query;
use yii\db\Expression;
use orders\models\Orders;
use yii\data\ActiveDataProvider;

class OrdersSearch implements IOrdersSearch
{
    /**
     * Тип поиска.
     */
    public const SEARCH_TYPE_ORDER_ID = 1;
    public const SEARCH_TYPE_LINK = 2;
    public const SEARCH_TYPE_USER_NAME = 3;

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
        $this->model->load($queryParams, ''); // Массовое присвоение

        // Проверяем входные данные
        // (сбрасываем значения при неудачной валидации)
        if (!$this->model->validate()) {
            $this->model = new Orders();
        }
    }

    /**
     * Возвращает результат поиска по заказам.
     * @return ActiveDataProvider
     */
    public function getData(): ActiveDataProvider
    {
        $query = $this->model::find();

        /**
         * Фильтрация по статусу заказа.
         */
        if (!is_null(
                $status = $this->model->attributes['status']
            ) && is_numeric($status)) {
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
         * Поиск по ID заказа, ссылке или имени пользователя.
         */
        if (!is_null($search = $this->model->attributes['search']) && strlen(
                $search
            )) {
            $search = trim($search);
            switch ($this->model->attributes['search_type']) {
                case self::SEARCH_TYPE_ORDER_ID:
                    $query->andWhere(['id' => $search]);
                    break;
                case self::SEARCH_TYPE_LINK:
                    $query->andWhere(['like', 'link', $search]);
                    break;
                case self::SEARCH_TYPE_USER_NAME:
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

        $adpParams = [
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => Yii::$app->params['orders_per_page']
            ]
        ];

        return new ActiveDataProvider($adpParams);
    }

    /**
     * Получение количества заказов по сервисам + общее количество заказов.
     * @return array
     */
    public function getOrdersCountByServices(): array
    {
        // Запрашиваем данные из БД.
        $byServicesQuery = (new Query())
            ->select(['service_id AS id', 'COUNT(*) AS count'])
            ->from('orders')
            ->groupBy(['service_id']);

        $totalQuery = (new Query())
            ->select([new Expression(0), 'COUNT(*)'])
            ->from('orders');

        // Фильтрация по режиму
        if (!is_null(
                $mode = $this->model->attributes['mode']
            ) && (int)$mode !== Orders::MODE_ALL) {
            $byServicesQuery->andWhere(['mode' => $mode]);
            $totalQuery->andWhere(['mode' => $mode]);
        }

        // Фильтрация по статусу
        if (!is_null(
                $status = $this->model->attributes['status']
            ) && is_numeric($status)) {
            $byServicesQuery->andWhere(['status' => $status]);
            $totalQuery->andWhere(['status' => $status]);
        }

        $byServicesQuery->union($totalQuery);

        $data = (new Query())
            ->select(['t1.id', 't1.count', 't2.name'])
            ->from(['t1' => $byServicesQuery])
            ->leftJoin('services AS t2', 't1.id = t2.id')
            ->orderBy('t1.count DESC')
            ->all();

        return empty($data) ? [] : $data;
    }

    /**
     * Получение списка типов поиска.
     * @return int[]
     */
    public static function getSearchTypes(): array
    {
        return [
            self::SEARCH_TYPE_ORDER_ID => Yii::t(
                'text',
                'orders.search.type.id'
            ),
            self::SEARCH_TYPE_LINK => Yii::t('text', 'orders.search.type.link'),
            self::SEARCH_TYPE_USER_NAME => Yii::t(
                'text',
                'orders.search.type.username'
            )
        ];
    }
}
