<?php

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "t_aid_plan".
 *
 * @property integer $id
 * @property integer $shelter_id
 * @property string $distribution_plan_date
 * @property integer $plan_status
 * @property string $remark
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
 * @property \app\models\AidDistribution[] $aidDistributions
 * @property \app\models\Shelter $shelter
 * @property \app\models\AidPlanDetails[] $aidPlanDetails
 */
class AidPlan extends \yii\db\ActiveRecord
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
            'aidDistributions',
            'shelter',
            'aidPlanDetails'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shelter_id', 'plan_status', 'created_by', 'updated_by', 'is_deleted', 'deleted_by', 'verlock'], 'integer'],
            [['distribution_plan_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['remark'], 'string'],
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
        return 't_aid_plan';
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
            'shelter_id' => Yii::t('app', 'Shelter ID'),
            'distribution_plan_date' => Yii::t('app', 'Distribution Plan Date'),
            'plan_status' => Yii::t('app', 'Plan Status'),
            'remark' => Yii::t('app', 'Remark'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAidDistributions()
    {
        return $this->hasMany(\app\models\AidDistribution::class, ['aid_plan_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShelter()
    {
        return $this->hasOne(\app\models\Shelter::class, ['id' => 'shelter_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAidPlanDetails()
    {
        return $this->hasMany(\app\models\AidPlanDetails::class, ['aid_plan_id' => 'id']);
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
     * @return \app\models\AidPlanQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \app\models\AidPlanQuery(get_called_class());
        return $query->where(['t_aid_plan.is_deleted' => 0]);
    }
}
