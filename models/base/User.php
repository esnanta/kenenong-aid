<?php

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "t_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $unconfirmed_email
 * @property string $registration_ip
 * @property integer $flags
 * @property integer $confirmed_at
 * @property integer $blocked_at
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $last_login_at
 * @property string $last_login_ip
 * @property string $auth_tf_key
 * @property integer $auth_tf_enabled
 * @property string $auth_tf_type
 * @property string $auth_tf_mobile_phone
 * @property integer $password_changed_at
 * @property integer $gdpr_consent
 * @property integer $gdpr_consent_date
 * @property integer $gdpr_deleted
 *
 * @property \app\models\Profile $profile
 * @property \app\models\SocialAccount[] $socialAccounts
 * @property \app\models\Token[] $tokens
 */
class User extends \yii\db\ActiveRecord
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
            'profile',
            'socialAccounts',
            'tokens'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password_hash', 'auth_key', 'updated_at', 'created_at'], 'required'],
            [['flags', 'confirmed_at', 'blocked_at', 'updated_at', 'created_at', 'last_login_at', 'password_changed_at', 'gdpr_consent_date'], 'integer'],
            [['username', 'email', 'unconfirmed_email'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip', 'last_login_ip'], 'string', 'max' => 45],
            [['auth_tf_key'], 'string', 'max' => 16],
            [['auth_tf_enabled', 'gdpr_consent', 'gdpr_deleted'], 'string', 'max' => 1],
            [['auth_tf_type', 'auth_tf_mobile_phone'], 'string', 'max' => 20],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['verlock'], 'default', 'value' => '0'],
            [['verlock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_user';
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
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'unconfirmed_email' => Yii::t('app', 'Unconfirmed Email'),
            'registration_ip' => Yii::t('app', 'Registration Ip'),
            'flags' => Yii::t('app', 'Flags'),
            'confirmed_at' => Yii::t('app', 'Confirmed At'),
            'blocked_at' => Yii::t('app', 'Blocked At'),
            'last_login_at' => Yii::t('app', 'Last Login At'),
            'last_login_ip' => Yii::t('app', 'Last Login Ip'),
            'auth_tf_key' => Yii::t('app', 'Auth Tf Key'),
            'auth_tf_enabled' => Yii::t('app', 'Auth Tf Enabled'),
            'auth_tf_type' => Yii::t('app', 'Auth Tf Type'),
            'auth_tf_mobile_phone' => Yii::t('app', 'Auth Tf Mobile Phone'),
            'password_changed_at' => Yii::t('app', 'Password Changed At'),
            'gdpr_consent' => Yii::t('app', 'Gdpr Consent'),
            'gdpr_consent_date' => Yii::t('app', 'Gdpr Consent Date'),
            'gdpr_deleted' => Yii::t('app', 'Gdpr Deleted'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(\app\models\Profile::className(), ['user_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(\app\models\SocialAccount::className(), ['user_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(\app\models\Token::className(), ['user_id' => 'id']);
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
     * @return \app\models\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \app\models\UserQuery(get_called_class());
        return $query->where(['t_user.deleted_by' => 0]);
    }
}
