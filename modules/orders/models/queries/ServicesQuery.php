<?php

namespace app\modules\orders\models\queries;

use yii\db\ActiveQuery;
use app\modules\orders\models\Services;

/**
 * This is the ActiveQuery class for [[Services]].
 *
 * @see Services
 */
class ServicesQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Services[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Services|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
