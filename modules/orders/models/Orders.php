<?php /** @noinspection PhpUnused */

namespace app\modules\orders\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use app\modules\orders\models\queries\OrdersQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property string $link
 * @property int $quantity
 * @property int $service_id
 * @property int $status
 * @property int $created_at
 * @property int $mode
 */
class Orders extends ActiveRecord
{
    /**
     * Тип поиска.
     */
    const SEARCH_TYPE_ORDER_ID = 1;
    const SEARCH_TYPE_LINK = 2;
    const SEARCH_TYPE_USER_NAME = 3;

    /**
     * Режим заказа.
     */
    const MODE_MANUAL = 0;
    const MODE_AUTO = 1;
    const MODE_ALL = 2;

    /**
     * Статус заказа.
     */
    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELED = 3;
    const STATUS_FAIL = 4;

    /**
     * Сценарии для валидации данных.
     */
    const SCENARIO_SEARCH = 'search';

    /**
     * Кастомные атрибуты.
     */
    public ?string $search = null;
    public ?int $search_type = null;

    /**
     * Добавляем кастомные атрибуты в модель.
     * @return array
     */
    public function attributes(): array
    {
        return ArrayHelper::merge(parent::attributes(), [
            'search',
            'search_type'
        ]);
    }

    /**
     * Получение списка типов поиска.
     * @return int[]
     */
    public static function getSearchTypes(): array {
        return [
            self::SEARCH_TYPE_ORDER_ID => Yii::t('text', 'orders.search.type.id'),
            self::SEARCH_TYPE_LINK => Yii::t('text', 'orders.search.type.link'),
            self::SEARCH_TYPE_USER_NAME => Yii::t('text', 'orders.search.type.username')
        ];
    }

    /**
     * Получение списка режимов заказа.
     * @return array
     */
    public static function getOrderModes(): array {
        return [
            self::MODE_MANUAL => Yii::t('text', 'orders.mode.manual'),
            self::MODE_AUTO => Yii::t('text', 'orders.mode.auto'),
            self::MODE_ALL => Yii::t('text', 'orders.mode.all')
        ];
    }

    /**
     * Получение статусов заказа.
     * @return array
     */
    public static function getOrderStatuses(): array {
        return [
            self::STATUS_PENDING => Yii::t('text', 'orders.status.pending'),
            self::STATUS_IN_PROGRESS => Yii::t('text', 'orders.status.inprogress'),
            self::STATUS_COMPLETED => Yii::t('text', 'orders.status.completed'),
            self::STATUS_CANCELED => Yii::t('text', 'orders.status.canceled'),
            self::STATUS_FAIL => Yii::t('text', 'orders.status.fail')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['status', 'mode', 'service_id', 'search_type'], 'integer', 'skipOnEmpty' => true, 'on' => self::SCENARIO_SEARCH],
            ['status', 'in', 'range' => array_keys(self::getOrderStatuses()), 'skipOnEmpty' => true, 'on' => self::SCENARIO_SEARCH],
            ['mode', 'in', 'range' => array_keys(self::getOrderModes()), 'skipOnEmpty' => true, 'on' => self::SCENARIO_SEARCH],
            ['search_type', 'in', 'range' => array_keys(self::getSearchTypes()), 'skipOnEmpty' => true, 'on' => self::SCENARIO_SEARCH],
            [['search'], 'string', 'min' => 1, 'max' => 500, 'skipOnEmpty' => true, 'on' => self::SCENARIO_SEARCH],
            [['id', 'user_id', 'link', 'quantity', 'created_at'], 'safe', 'on' => self::SCENARIO_SEARCH]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('text', 'orders.grid.column.id'),
            'user_id' => Yii::t('text', 'orders.grid.column.user'),
            'link' => Yii::t('text', 'orders.grid.column.link'),
            'quantity' => Yii::t('text', 'orders.grid.column.quantity'),
            'service_id' => Yii::t('text', 'orders.grid.column.service'),
            'status' => Yii::t('text', 'orders.grid.column.status'),
            'created_at' => Yii::t('text', 'orders.grid.column.created'),
            'mode' => Yii::t('text', 'orders.grid.column.mode'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OrdersQuery the active query used by this AR class.
     */
    public static function find(): OrdersQuery
    {
        return new OrdersQuery(get_called_class());
    }

    /**
     * Связанный с заказом пользователь.
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery {
        return $this->hasOne(Users::class, [
            'id' => 'user_id'
        ]);
    }

    /**
     * Связанный с заказом сервис.
     * @return ActiveQuery
     */
    public function getService(): ActiveQuery
    {
        return $this->hasOne(Services::class, [
            'id' => 'service_id'
        ]);
    }

    /**
     * Получение количества заказов по сервисам + общее количество заказов.
     * @return array
     */
    public static function getOrdersCountByServices(): array {
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
