<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MediaFile]].
 *
 * @see MediaFile
 */
class MediaFileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return MediaFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MediaFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
