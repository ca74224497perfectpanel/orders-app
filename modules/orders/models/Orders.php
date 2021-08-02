<?php

namespace app\modules\orders\models;

use Yii;
use yii\db\ActiveRecord;

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
     * Получение списка типов поиска.
     * @return int[]
     */
    public static function getSearchTypes(): array {
        return [
            self::SEARCH_TYPE_ORDER_ID => Yii::t('text', 'Order ID'),
            self::SEARCH_TYPE_LINK => Yii::t('text', 'Link'),
            self::SEARCH_TYPE_USER_NAME => Yii::t('text', 'Username')
        ];
    }

    /**
     * Получение списка режимов заказа.
     * @return array
     */
    public static function getOrderModes(): array {
        return [
            self::MODE_MANUAL => Yii::t('text', 'Manual'),
            self::MODE_AUTO => Yii::t('text', 'Auto'),
            self::MODE_ALL => Yii::t('text', 'All')
        ];
    }

    /**
     * Получение статусов заказа.
     * @return array
     */
    public static function getOrderStatuses(): array {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELED => 'Canceled',
            self::STATUS_FAIL => 'Fail'
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
            [['user_id', 'link', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'required'],
            [['user_id', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'integer'],
            [['link'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('text', 'ID'),
            'user_id' => Yii::t('text', 'User'),
            'link' => Yii::t('text', 'Link'),
            'quantity' => Yii::t('text', 'Quantity'),
            'service_id' => Yii::t('text', 'Service'),
            'status' => Yii::t('text', 'Status'),
            'created_at' => Yii::t('text', 'Created'),
            'mode' => Yii::t('text', 'Mode'),
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
     * @return UsersQuery
     */
    public function getUser(): UsersQuery {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }
}
