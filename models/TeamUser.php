<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * TeamUser model (pivot table)
 *
 * @property integer $id
 * @property integer $team_id
 * @property integer $user_id
 * @property string $role
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class TeamUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%team_user}}';
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
            [['team_id', 'user_id'], 'required'],
            ['role', 'string', 'max' => 255],
            [['team_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * Get team relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
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
}


