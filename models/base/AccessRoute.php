<?php

namespace app\models\base;

use app\models\AccessRouteQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "t_access_route".
 *
 * @property integer $id
 * @property integer $disaster_id
 * @property string $route_name
 * @property string $route_geometry
 * @property string $route_length_km
 * @property integer $access_route_status_id
 * @property string $geometry_updated_at
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $is_deleted
 * @property string $deleted_at
 * @property integer $deleted_by
 * @property integer $verlock
 * @property string $uuid
 *
 * @property \app\models\Disaster $disaster
 * @property \app\models\AccessRouteStatus $accessRouteStatus
 * @property \app\models\AccessRouteShelters[] $accessRouteShelters
 * @property \app\models\AccessRouteVehicles[] $accessRouteVehicles
 */
class AccessRoute extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    private $_rt_softdelete;
    private $_rt_softrestore;

    public function __construct(){
        parent::__construct();
        $this->_rt_softdelete = [
            'deleted_by' => \Yii::$app->user->id,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
        $this->_rt_softrestore = [
            'deleted_by' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
    }

    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'disaster',
            'accessRouteStatus',
            'accessRouteShelters',
            'accessRouteVehicles'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disaster_id', 'access_route_status_id', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['route_geometry', 'description'], 'string'],
            [['route_length_km'], 'number'],
            [['geometry_updated_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['route_name'], 'string', 'max' => 255],
            [['is_deleted'], 'string', 'max' => 1],
            [['uuid'], 'string', 'max' => 36],
            [['verlock'], 'default', 'value' => '0'],
            [['verlock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_access_route';
    }

    /**
     *
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock
     *
     */
    public function optimisticLock() {
        return 'verlock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
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
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisaster()
    {
        return $this->hasOne(\app\models\Disaster::class, ['id' => 'disaster_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessRouteStatus()
    {
        return $this->hasOne(\app\models\AccessRouteStatus::class, ['id' => 'access_route_status_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessRouteShelters()
    {
        return $this->hasMany(\app\models\AccessRouteShelters::class, ['access_route_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessRouteVehicles()
    {
        return $this->hasMany(\app\models\AccessRouteVehicles::class, ['access_route_id' => 'id']);
    }
    
    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'uuid' => [
                'class' => UUIDBehavior::class,
                'column' => 'uuid',
            ],
        ];
    }

    /**
     * The following code shows how to apply a default condition for all queries:
     *
     * ```php
     * class Customer extends ActiveRecord
     * {
     *     public static function find()
     *     {
     *         return parent::find()->where(['deleted' => false]);
     *     }
     * }
     *
     * // Use andWhere()/orWhere() to apply the default condition
     * // SELECT FROM customer WHERE `deleted`=:deleted AND age>30
     * $customers = Customer::find()->andWhere('age>30')->all();
     *
     * // Use where() to ignore the default condition
     * // SELECT FROM customer WHERE age>30
     * $customers = Customer::find()->where('age>30')->all();
     * ```
     */

    /**
     * @inheritdoc
     * @return AccessRouteQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new AccessRouteQuery(get_called_class());
        return $query->where(['t_access_route.is_deleted' => 0]);
    }
}
