<?php

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "t_aid_distribution_details".
 *
 * @property integer $id
 * @property integer $aid_distribution_id
 * @property integer $item_id
 * @property integer $quantity
 * @property integer $unit_id
 * @property integer $verlock
 * @property string $uuid
 *
 * @property \app\models\Item $item
 * @property \app\models\AidDistribution $aidDistribution
 * @property \app\models\Units $unit
 */
class AidDistributionDetails extends \yii\db\ActiveRecord
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
            'item',
            'aidDistribution',
            'unit'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid_distribution_id', 'item_id', 'quantity', 'unit_id', 'verlock'], 'integer'],
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
        return 't_aid_distribution_details';
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
            'aid_distribution_id' => Yii::t('app', 'Aid Distribution ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(\app\models\Item::className(), ['id' => 'item_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAidDistribution()
    {
        return $this->hasOne(\app\models\AidDistribution::className(), ['id' => 'aid_distribution_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(\app\models\Units::className(), ['id' => 'unit_id']);
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
     * @return \app\models\AidDistributionDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \app\models\AidDistributionDetailsQuery(get_called_class());
        return $query->where(['t_aid_distribution_details.deleted_by' => 0]);
    }
}
