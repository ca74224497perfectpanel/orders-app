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
 * @property int $status 0 - Pending, 1 - In progress, 2 - Completed, 3 - Canceled, 4 - Fail
 * @property int $created_at
 * @property int $mode 0 - Manual, 1 - Auto
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
     * Список статусов заказа.
     */
    const ORDER_STATUSES = [
        0 => 'Pending',
        1 => 'In progress',
        2 => 'Completed',
        3 => 'Canceled',
        4 => 'Fail'
    ];

    /**
     * Список режимов.
     */
    const MODE_STATUSES = [
        0 => 'Manual',
        1 => 'Auto'
    ];

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
}
