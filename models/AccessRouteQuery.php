<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[AccessRoute]].
 *
 * @see AccessRoute
 */
class AccessRouteQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AccessRoute[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccessRoute|null
     */
    public function one($db = null)
    {
        /** @var AccessRoute|null $result */
        $result = parent::one($db);
        return $result;
    }
}
