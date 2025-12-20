<?php

namespace app\models;

use Yii;
use \app\models\base\AccessRouteShelter as BaseAccessRouteShelters;

/**
 * This is the model class for table "t_access_route_shelters".
 */
class AccessRouteShelter extends BaseAccessRouteShelters
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['access_route_id', 'shelter_id', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['is_deleted'], 'integer'],
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
            'access_route_id' => Yii::t('app', 'Access Route ID'),
            'shelter_id' => Yii::t('app', 'Shelter ID'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
}
