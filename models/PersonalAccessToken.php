<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * PersonalAccessToken model
 *
 * @property integer $id
 * @property string $tokenable_type
 * @property integer $tokenable_id
 * @property string $name
 * @property string $token
 * @property string $abilities (JSON)
 * @property timestamp $last_used_at
 * @property timestamp $expires_at
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class PersonalAccessToken extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%personal_access_tokens}}';
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tokenable_type', 'tokenable_id', 'name', 'token'], 'required'],
            [['tokenable_type', 'name'], 'string', 'max' => 255],
            ['token', 'string', 'max' => 64],
            ['token', 'unique'],
            ['abilities', 'string'], // JSON stored as text
            [['last_used_at', 'expires_at'], 'safe'],
            ['tokenable_id', 'integer'],
        ];
    }

    /**
     * Check if token is expired
     * 
     * @return bool
     */
    public function isExpired()
    {
        if (!$this->expires_at) {
            return false; // No expiration
        }
        return strtotime($this->expires_at) < time();
    }

    /**
     * Get abilities as array (decode JSON)
     * 
     * @return array
     */
    public function getAbilitiesArray()
    {
        if (empty($this->abilities)) {
            return [];
        }
        return json_decode($this->abilities, true) ?: [];
    }
}


