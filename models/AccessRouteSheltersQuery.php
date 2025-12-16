<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AccessRouteShelters]].
 *
 * @see AccessRouteShelters
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
     * @return AccessRouteShelters[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccessRouteShelters|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
