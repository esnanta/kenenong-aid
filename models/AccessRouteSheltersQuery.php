<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[AccessRouteShelters]].
 *
 * @see AccessRouteShelter
 */
class AccessRouteSheltersQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AccessRouteShelter[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccessRouteShelter|null
     */
    public function one($db = null): ?AccessRouteShelter
    {
        /** @var AccessRouteShelter|null $result */
        $result = parent::one($db);
        return $result;
    }
}
