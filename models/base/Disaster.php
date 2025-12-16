<?php

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "t_disaster".
 *
 * @property integer $id
 * @property string $title
 * @property integer $disaster_type_id
 * @property integer $disaster_status_id
 * @property string $start_date
 * @property string $end_date
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
 * @property \app\models\AccessRoute[] $accessRoutes
 * @property \app\models\DisasterStatus $disasterStatus
 * @property \app\models\DisasterType $disasterType
 * @property \app\models\Shelter[] $shelters
 */
class Disaster extends \yii\db\ActiveRecord
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
            'accessRoutes',
            'disasterStatus',
            'disasterType',
            'shelters'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disaster_type_id', 'disaster_status_id', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
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
        return 't_disaster';
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
            'title' => Yii::t('app', 'Title'),
            'disaster_type_id' => Yii::t('app', 'Disaster Type ID'),
            'disaster_status_id' => Yii::t('app', 'Disaster Status ID'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'description' => Yii::t('app', 'Description'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessRoutes()
    {
        return $this->hasMany(\app\models\AccessRoute::className(), ['disaster_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisasterStatus()
    {
        return $this->hasOne(\app\models\DisasterStatus::className(), ['id' => 'disaster_status_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisasterType()
    {
        return $this->hasOne(\app\models\DisasterType::className(), ['id' => 'disaster_type_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShelters()
    {
        return $this->hasMany(\app\models\Shelter::className(), ['disaster_id' => 'id']);
    }
    
    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'uuid' => [
                'class' => UUIDBehavior::className(),
                'column' => 'uuid',
            ],
        ];
    }
}
