<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * TeamInvitation model
 *
 * @property integer $id
 * @property integer $team_id
 * @property string $email
 * @property string $role
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class TeamInvitation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%team_invitations}}';
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
            [['team_id', 'email'], 'required'],
            ['email', 'email'],
            ['role', 'string', 'max' => 255],
            ['team_id', 'integer'],
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
}


