<?php

namespace orders\models\search;

use Yii;
use Exception;
use yii\db\Query;
use orders\models\Orders;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use orders\widgets\ModeDropdown;
use orders\widgets\ServiceDropdown;

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

        $query = $this->queryFilter(
            ['mode', 'status', 'search', 'service'],
            $query
        );

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

        $byServicesQuery = $this->queryFilter(
            ['mode', 'status', 'search'],
            $byServicesQuery
        );

        $data = (new Query())
            ->select(['t1.id', 't1.count', 't2.name'])
            ->from(['t1' => $byServicesQuery])
            ->leftJoin('services AS t2', 't1.id = t2.id')
            ->orderBy('t1.count DESC')
            ->all();

        // Добавляем строку "total" в результирующий набор.
        $total = array_reduce(
            $data, fn($carry, $item) => $carry += $item['count']
        );
        $data = ArrayHelper::merge(
            [['id' => 0, 'count' => $total]],
            $data
        );

        return empty($data) ? [] : $data;
    }

    /**
     * Применение фильтра к запросу.
     * @param array $input
     * @param Query $query
     * @return Query
     */
    public function queryFilter(array $input, Query $query): Query
    {
        // Фильтрация по режиму
        if (in_array('mode', $input) && !is_null(
                $mode = $this->model->attributes['mode']
            ) && (int)$mode !== Orders::MODE_ALL) {
            $query->andWhere(['mode' => $mode]);
        }

        // Фильтрация по статусу
        if (in_array('status', $input) && !is_null(
                $status = $this->model->attributes['status']
            ) && is_numeric($status)) {
            $query->andWhere(['status' => $status]);
        }

        // Фильтрация по сервису
        if (in_array('service', $input) && !is_null(
                $service = $this->model->attributes['service_id']
            )) {
            $query->andWhere(['service_id' => $service]);
        }

        // Фильтрация по поиску
        if (in_array('search', $input) && !is_null(
                $search = $this->model->attributes['search']
            ) && strlen($search)) {
            $search = trim($search);
            switch ($this->model->attributes['search_type']) {
                case self::SEARCH_TYPE_ORDER_ID:
                    $query->andWhere(['id' => $search]);
                    break;
                case self::SEARCH_TYPE_LINK:
                    $query->andWhere(['like', 'link', $search]);
                    break;
                case self::SEARCH_TYPE_USER_NAME:
                    $query->leftJoin(
                        'users',
                        'users.id = orders.user_id'
                    )->andWhere(
                        ['like', "CONCAT(first_name, ' ', last_name)", $search]
                    );
                    break;
            }
        }

        return $query;
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

    /**
     * Возвращает определение колонок дял таблицы поиска результатов.
     * @param false $isCsv
     * @return array
     * @throws Exception
     */
    public function getColumnsDefinition(bool $isCsv = false): array
    {
        $dateYmd = fn($d) => date('Y-m-d', $d);
        $dateHis = fn($d) => date('H:i:s', $d);

        $columns = [
            'id',
            [
                'attribute' => 'user_id',
                'value' => function ($item) {
                    return "{$item->user->first_name} {$item->user->last_name}";
                }
            ],
            [
                'attribute' => 'link',
                'contentOptions' => ['class' => 'link']
            ],
            'quantity',
            [
                'header' => ServiceDropdown::widget(),
                'attribute' => 'service_id',
                'contentOptions' => ['class' => 'service'],
                'headerOptions' => ['class' => 'dropdown-th'],
                'format' => 'html',
                'value' => function ($item) {
                    return "<span class='label-id'>{$item->service->id}</span> 
                        {$item->service->name}";
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($item) {
                    return Orders::getStatusLabelById($item->status);
                }
            ],
            [
                'header' => ModeDropdown::widget(),
                'attribute' => 'mode',
                'headerOptions' => ['class' => 'dropdown-th'],
                'value' => function ($item) {
                    return Orders::getModeLabelById($item->mode);
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => 'html',
                'value' => fn($item) => '<span class="nowrap">' . $dateYmd(
                        $item->created_at
                    ) . '</span><span class="nowrap">' . $dateHis(
                        $item->created_at
                    ) . '</span>'
            ]
        ];

        if ($isCsv) {
            // Редактируем колонки для репрезентации в csv-формате.

            $columns[2] = ['attribute' => 'link'];
            $columns[4] = [
                'attribute' => 'service_id',
                'value' => function ($item) {
                    return "{$item->service->name} ({$item->service->id})";
                }
            ];

            unset($columns[6]['header']);
            unset($columns[6]['headerOptions']);

            $columns[7] = [
                'attribute' => 'created_at',
                'value' => fn($item) => $dateYmd(
                        $item->created_at
                    ) . PHP_EOL . $dateHis($item->created_at)
            ];
        }

        return $columns;
    }
}
