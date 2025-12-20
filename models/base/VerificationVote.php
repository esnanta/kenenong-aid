<?php

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "t_verification_vote".
 *
 * @property integer $id
 * @property integer $verification_id
 * @property integer $verification_action_id
 * @property string $notes
 * @property integer $voted_by
 * @property string $voted_at
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
 * @property \app\models\VerificationAction $verificationAction
 * @property \app\models\Verification $verification
 * @property \app\models\Users $votedBy
 */
class VerificationVote extends \yii\db\ActiveRecord
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
            'verificationAction',
            'verification',
            'votedBy'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['verification_id', 'verification_action_id', 'voted_by', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['notes'], 'string'],
            [['voted_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['is_deleted'], 'string', 'max' => 1],
            [['uuid'], 'string', 'max' => 36],
            [['verification_id', 'voted_by'], 'unique', 'targetAttribute' => ['verification_id', 'voted_by'], 'message' => 'The combination of Verification ID and Voted By has already been taken.'],
            [['verlock'], 'default', 'value' => '0'],
            [['verlock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_verification_vote';
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
            'verification_id' => Yii::t('app', 'Verification ID'),
            'verification_action_id' => Yii::t('app', 'Verification Action ID'),
            'notes' => Yii::t('app', 'Notes'),
            'voted_by' => Yii::t('app', 'Voted By'),
            'voted_at' => Yii::t('app', 'Voted At'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerificationAction()
    {
        return $this->hasOne(\app\models\VerificationAction::class, ['id' => 'verification_action_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerification()
    {
        return $this->hasOne(\app\models\Verification::class, ['id' => 'verification_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotedBy()
    {
        return $this->hasOne(\app\models\Users::class, ['id' => 'voted_by']);
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
     * @return \app\models\VerificationVoteQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \app\models\VerificationVoteQuery(get_called_class());
        return $query->where(['t_verification_vote.is_deleted' => 0]);
    }
}
