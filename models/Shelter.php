<?php

namespace app\models;

use Yii;
use \app\models\base\Shelter as BaseShelter;

/**
 * This is the model class for table "t_shelter".
 */
class Shelter extends BaseShelter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['disaster_id', 'evacuee_count', 'created_by', 'updated_by', 'is_deleted', 'deleted_by', 'verlock'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['aid_status', 'description'], 'string'],
            [['last_aid_distribution_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['uuid'], 'string', 'max' => 36],
            [['verlock'], 'default', 'value' => '0'],
            [['verlock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'disaster_id' => Yii::t('app', 'Disaster ID'),
            'title' => Yii::t('app', 'Title'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'evacuee_count' => Yii::t('app', 'Evacuee Count'),
            'aid_status' => Yii::t('app', 'Aid Status'),
            'last_aid_distribution_at' => Yii::t('app', 'Last Aid Distribution At'),
            'description' => Yii::t('app', 'Description'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
}
