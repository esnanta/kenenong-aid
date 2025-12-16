<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Unit]].
 *
 * @see Unit
 */
class UnitQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Unit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Unit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
