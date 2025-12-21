<?php

namespace app\models;

use Yii;
use Da\User\Model\User as BaseUser;

/**
 * This is the model class for table "t_user".
 */
class User extends BaseUser
{
    const STATUS_TRASHED = 1; // Example status for soft delete

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['username', 'email', 'password_hash', 'auth_key', 'updated_at', 'created_at'], 'required'],
            [['flags', 'confirmed_at', 'blocked_at', 'updated_at', 'created_at', 'last_login_at', 'password_changed_at', 'gdpr_consent_date', 'verlock'], 'integer'],
            [['username', 'email', 'unconfirmed_email'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip', 'last_login_ip'], 'string', 'max' => 45],
            [['auth_tf_key'], 'string', 'max' => 16],
            [['auth_tf_enabled', 'gdpr_consent', 'gdpr_deleted'], 'string', 'max' => 1],
            [['auth_tf_type', 'auth_tf_mobile_phone'], 'string', 'max' => 20],
            [['uuid'], 'string', 'max' => 36],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id']);
    }

    /**
     * Performs a soft delete on the user.
     * Sets the user's status to 'trashed'.
     * @return bool whether the user was successfully trashed
     * @throws \yii\db\Exception
     */
    public function trash(): bool
    {
        $this->flags = self::STATUS_TRASHED; // Assuming 'flags' can be used for status
        return $this->save(false, ['flags']); // Save only the 'flags' attribute
    }
}
