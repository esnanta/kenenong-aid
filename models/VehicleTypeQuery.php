<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VehicleType]].
 *
 * @see VehicleType
 */
class VehicleTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VehicleType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VehicleType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
