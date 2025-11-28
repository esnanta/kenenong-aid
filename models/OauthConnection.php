<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * OauthConnection model
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $provider
 * @property string $provider_id
 * @property string $data (JSON)
 * @property string $token
 * @property string $refresh_token
 * @property timestamp $expires_at
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class OauthConnection extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth_connections}}';
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
            [['user_id', 'provider', 'provider_id'], 'required'],
            [['provider', 'provider_id', 'token', 'refresh_token'], 'string', 'max' => 255],
            ['data', 'string'], // JSON stored as text
            ['expires_at', 'safe'],
        ];
    }

    /**
     * Get user relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Get data as array (decode JSON)
     * 
     * @return array|null
     */
    public function getDataArray()
    {
        if (empty($this->data)) {
            return null;
        }
        return json_decode($this->data, true);
    }

    /**
     * Set data as array (encode to JSON)
     * 
     * @param array $data
     */
    public function setDataArray($data)
    {
        $this->data = json_encode($data);
    }
}


