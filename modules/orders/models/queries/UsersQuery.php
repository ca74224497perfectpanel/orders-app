<?php

namespace orders\models\queries;

use yii\db\ActiveQuery;
use orders\models\Users;

/**
 * This is the ActiveQuery class for [[Users]].
 *
 * @see Users
 */
class UsersQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Users[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Users|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
