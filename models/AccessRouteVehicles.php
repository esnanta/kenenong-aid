<?php

namespace app\models;

use Yii;
use \app\models\base\AccessRouteVehicles as BaseAccessRouteVehicles;

/**
 * This is the model class for table "t_access_route_vehicles".
 */
class AccessRouteVehicles extends BaseAccessRouteVehicles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['access_route_id', 'vehicle_type_id', 'created_by', 'updated_by', 'verlock'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['is_deleted'], 'integer'],
            [['deleted_by'], 'string', 'max' => 255],
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
            'vehicle_type_id' => Yii::t('app', 'Vehicle Type ID'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
}
