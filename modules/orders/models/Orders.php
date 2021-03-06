<?php
/** @noinspection PhpUnused */

namespace orders\models;

use orders\models\search\OrdersSearch;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use orders\models\queries\OrdersQuery;

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
     * Режим заказа.
     */
    public const MODE_MANUAL = 0;
    public const MODE_AUTO = 1;
    public const MODE_ALL = 2;

    /**
     * Статус заказа.
     */
    public const STATUS_PENDING = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELED = 3;
    public const STATUS_FAIL = 4;

    /**
     * Сценарии для валидации данных.
     */
    public const SCENARIO_SEARCH = 'search';

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
     * Получение списка режимов заказа.
     * @return array
     */
    public static function getOrderModes(): array
    {
        return [
            self::MODE_ALL => Yii::t('text', 'orders.mode.all'),
            self::MODE_AUTO => Yii::t('text', 'orders.mode.auto'),
            self::MODE_MANUAL => Yii::t('text', 'orders.mode.manual')
        ];
    }

    /**
     * Получение статусов заказа.
     * @return array
     */
    public static function getOrderStatuses(): array
    {
        return [
            self::STATUS_PENDING => Yii::t('text', 'orders.status.pending'),
            self::STATUS_IN_PROGRESS => Yii::t(
                'text',
                'orders.status.inprogress'
            ),
            self::STATUS_COMPLETED => Yii::t('text', 'orders.status.completed'),
            self::STATUS_CANCELED => Yii::t('text', 'orders.status.canceled'),
            self::STATUS_FAIL => Yii::t('text', 'orders.status.fail')
        ];
    }

    /**
     * Возвращает текстовое описание для указанного статуса.
     * @param int $id
     * @return string
     */
    public static function getStatusLabelById(int $id): string
    {
        return self::getOrderStatuses()[$id];
    }

    /**
     * Возвращает текстовое описание для указанного режима.
     * @param int $id
     * @return string
     */
    public static function getModeLabelById(int $id): string
    {
        return self::getOrderModes()[$id];
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
            [
                ['mode', 'service_id', 'search_type'],
                'integer',
                'skipOnEmpty' => true,
                'on' => self::SCENARIO_SEARCH
            ],
            [
                'status',
                'in',
                'range' => ArrayHelper::merge(
                    array_keys(self::getOrderStatuses()),
                    ['all']
                ),
                'skipOnEmpty' => true,
                'on' => self::SCENARIO_SEARCH
            ],
            [
                'mode',
                'in',
                'range' => array_keys(self::getOrderModes()),
                'skipOnEmpty' => true,
                'on' => self::SCENARIO_SEARCH
            ],
            [
                'search_type',
                'in',
                'range' => array_keys(OrdersSearch::getSearchTypes()),
                'skipOnEmpty' => true,
                'on' => self::SCENARIO_SEARCH
            ],
            [
                ['search'],
                'string',
                'min' => 1,
                'max' => 500,
                'skipOnEmpty' => true,
                'on' => self::SCENARIO_SEARCH
            ],
            [
                ['id', 'user_id', 'link', 'quantity', 'created_at'],
                'safe',
                'on' => self::SCENARIO_SEARCH
            ]
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
    public function getUser(): ActiveQuery
    {
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
}
