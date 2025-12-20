<?php

namespace app\models;

use Yii;
use \app\models\base\AccessRoute as BaseAccessRoute;

/**
 * This is the model class for table "t_access_route".
 */
class AccessRoute extends BaseAccessRoute
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['disaster_id', 'access_route_status_id', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['route_geometry', 'description'], 'string'],
            [['route_length_km'], 'number'],
            [['geometry_updated_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['route_name'], 'string', 'max' => 255],
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
            'disaster_id' => Yii::t('app', 'Disaster ID'),
            'route_name' => Yii::t('app', 'Route Name'),
            'route_geometry' => Yii::t('app', 'Route Geometry'),
            'route_length_km' => Yii::t('app', 'Route Length Km'),
            'access_route_status_id' => Yii::t('app', 'Access Route Status ID'),
            'geometry_updated_at' => Yii::t('app', 'Geometry Updated At'),
            'description' => Yii::t('app', 'Description'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
}
