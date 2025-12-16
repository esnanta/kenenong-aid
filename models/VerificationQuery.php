<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Verification]].
 *
 * @see Verification
 */
class VerificationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Verification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Verification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
