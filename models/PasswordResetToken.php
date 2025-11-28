<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * PasswordResetToken model
 *
 * @property string $email
 * @property string $token
 * @property timestamp $created_at
 */
class PasswordResetToken extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%password_reset_tokens}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'token'], 'required'],
            ['email', 'email'],
            ['created_at', 'safe'],
        ];
    }

    /**
     * Find token by token string
     * 
     * @param string $token
     * @return static|null
     */
    public static function findByToken($token)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * Check if token is expired (older than 1 hour)
     * 
     * @return bool
     */
    public function isExpired()
    {
        if (!$this->created_at) {
            return true;
        }
        $created = strtotime($this->created_at);
        $expires = $created + 3600; // 1 hour
        return time() > $expires;
    }
}


