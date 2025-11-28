<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Team model
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property boolean $personal_team
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class Team extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%teams}}';
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
            [['user_id', 'name'], 'required'],
            ['name', 'string', 'max' => 255],
            ['personal_team', 'boolean'],
            ['user_id', 'integer'],
        ];
    }

    /**
     * Get owner relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Get members relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('{{%team_user}}', ['team_id' => 'id']);
    }

    /**
     * Get invitations relationship
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getInvitations()
    {
        return $this->hasMany(TeamInvitation::class, ['team_id' => 'id']);
    }
}


