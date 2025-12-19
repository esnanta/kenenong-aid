<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AidPlan]].
 *
 * @see AidPlan
 */
class AidPlanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AidPlan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AidPlan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
