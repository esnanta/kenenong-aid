<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AidDistribution]].
 *
 * @see AidDistribution
 */
class AidDistributionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AidDistribution[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AidDistribution|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
