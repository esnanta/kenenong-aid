<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * SubscriptionItem model
 *
 * @property integer $id
 * @property integer $subscription_id
 * @property string $stripe_id
 * @property string $stripe_product
 * @property string $stripe_price
 * @property integer $quantity
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class SubscriptionItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subscription_items}}';
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
            [['subscription_id', 'stripe_id', 'stripe_product', 'stripe_price'], 'required'],
            [['stripe_id', 'stripe_product', 'stripe_price'], 'string', 'max' => 255],
            ['quantity', 'integer'],
        ];
    }

    /**
     * Get subscription relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription()
    {
        return $this->hasOne(Subscription::class, ['id' => 'subscription_id']);
    }
}


