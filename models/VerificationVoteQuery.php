<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VerificationVote]].
 *
 * @see VerificationVote
 */
class VerificationVoteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VerificationVote[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VerificationVote|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
