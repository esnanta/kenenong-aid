<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use app\behaviors\SoftDeleteBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $password_hash (stored)
 * @property string $remember_token
 * @property integer $current_team_id
 * @property string $profile_photo_path
 * @property string $two_factor_secret
 * @property string $two_factor_recovery_codes
 * @property timestamp $two_factor_confirmed_at
 * @property string $stripe_id
 * @property string $pm_type
 * @property string $pm_last_four
 * @property timestamp $trial_ends_at
 * @property timestamp $email_verified_at
 * @property timestamp $deleted_at
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password; // Virtual attribute for password input

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new \yii\db\Expression('NOW()'),
            ],
            SoftDeleteBehavior::class,
        ];
    }

    /**
     * Override find() to exclude soft-deleted records by default
     * 
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->andWhere(['deleted_at' => null]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'filter' => function ($query) {
                // Exclude current user when updating
                if (!$this->isNewRecord) {
                    $query->andWhere(['!=', 'id', $this->id]);
                }
            }],
            ['name', 'string', 'max' => 255],
            ['password', 'string', 'min' => 6],
            ['password', 'required', 'on' => ['register', 'create']],
            ['remember_token', 'string', 'max' => 100],
            ['current_team_id', 'integer'],
            ['profile_photo_path', 'string', 'max' => 2048],
            [['email_verified_at', 'two_factor_confirmed_at', 'trial_ends_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'remember_token' => 'Remember Token',
            'current_team_id' => 'Current Team ID',
            'profile_photo_path' => 'Profile Photo Path',
            'email_verified_at' => 'Email Verified At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Use PersonalAccessToken model for token-based auth
        $tokenModel = PersonalAccessToken::findOne(['token' => $token]);
        if ($tokenModel && !$tokenModel->isExpired()) {
            return static::findIdentity($tokenModel->tokenable_id);
        }
        return null;
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        try {
            return static::findOne(['email' => $email]);
        } catch (\Exception $e) {
            // Log the error but return null to allow graceful error handling
            Yii::error('Error finding user by email: ' . $e->getMessage(), 'application');
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->remember_token;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->remember_token === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        // Get the hashed password from database (stored in 'password' column)
        $hashedPassword = $this->getAttribute('password');
        if (empty($hashedPassword)) {
            return false;
        }
        return Yii::$app->security->validatePassword($password, $hashedPassword);
    }

    /**
     * Generates password hash from password and sets it to the model
     * Note: This sets the virtual $password attribute, which will be hashed in beforeSave()
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password; // Virtual attribute, will be hashed in beforeSave()
    }

    /**
     * Generates "remember me" authentication token
     */
    public function generateRememberToken()
    {
        $this->remember_token = Yii::$app->security->generateRandomString(60);
    }

    /**
     * Check if email is verified
     * 
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified()
    {
        $this->email_verified_at = date('Y-m-d H:i:s');
        return $this->save(false, ['email_verified_at']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Handle password hashing - password is a virtual attribute
            // When password is set, hash it and store in the password column
            if (!empty($this->password)) {
                $hashedPassword = Yii::$app->security->generatePasswordHash($this->password);
                $this->setAttribute('password', $hashedPassword);
                // Clear virtual attribute
                unset($this->password);
            }
            if ($insert && empty($this->remember_token)) {
                $this->generateRememberToken();
            }
            return true;
        }
        return false;
    }

    /**
     * Get current team relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'current_team_id']);
    }

    /**
     * Get teams relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(Team::class, ['user_id' => 'id']);
    }

    /**
     * Get subscriptions relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::class, ['user_id' => 'id']);
    }

    /**
     * Get OAuth connections relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getOauthConnections()
    {
        return $this->hasMany(OauthConnection::class, ['user_id' => 'id']);
    }
}
