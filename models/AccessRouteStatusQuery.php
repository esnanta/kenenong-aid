<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AccessRouteStatus]].
 *
 * @see AccessRouteStatus
 */
class AccessRouteStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AccessRouteStatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccessRouteStatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
