<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AccessRouteShelters]].
 *
 * @see AccessRouteShelter
 */
class AccessRouteSheltersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AccessRouteShelter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccessRouteShelter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
