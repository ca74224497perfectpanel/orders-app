<?php

namespace app\modules\orders\models;

use yii\db\ActiveRecord;
use app\modules\orders\models\queries\UsersQuery;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 */
class Users extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 300],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
        ];
    }

    /**
     * @return UsersQuery
     */
    public static function find(): UsersQuery
    {
        return new UsersQuery(get_called_class());
    }
}
