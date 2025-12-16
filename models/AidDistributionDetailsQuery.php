<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AidDistributionDetails]].
 *
 * @see AidDistributionDetails
 */
class AidDistributionDetailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AidDistributionDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AidDistributionDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
