<?php

namespace orders\models;

use yii\db\ActiveRecord;
use orders\models\queries\ServicesQuery;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 */
class Services extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'services';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 300],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return ServicesQuery
     */
    public static function find(): ServicesQuery
    {
        return new ServicesQuery(get_called_class());
    }
}
