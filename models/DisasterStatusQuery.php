<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DisasterStatus]].
 *
 * @see DisasterStatus
 */
class DisasterStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return DisasterStatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DisasterStatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
