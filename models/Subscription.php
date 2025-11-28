<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Subscription model
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $stripe_id
 * @property string $stripe_status
 * @property string $stripe_price
 * @property integer $quantity
 * @property timestamp $trial_ends_at
 * @property timestamp $ends_at
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class Subscription extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subscriptions}}';
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
            [['user_id', 'type', 'stripe_id', 'stripe_status'], 'required'],
            [['type', 'stripe_id', 'stripe_status', 'stripe_price'], 'string', 'max' => 255],
            ['quantity', 'integer'],
            [['trial_ends_at', 'ends_at'], 'safe'],
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
     * Get items relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(SubscriptionItem::class, ['subscription_id' => 'id']);
    }
}


