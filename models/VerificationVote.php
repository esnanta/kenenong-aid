<?php

namespace app\models;

use Yii;
use \app\models\base\VerificationVote as BaseVerificationVote;

/**
 * This is the model class for table "t_verification_vote".
 */
class VerificationVote extends BaseVerificationVote
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['verification_id', 'verification_type_id', 'voted_by', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['notes'], 'string'],
            [['voted_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['is_deleted'], 'integer'],
            [['uuid'], 'string', 'max' => 36],
            [['verification_id', 'voted_by'], 'unique', 'targetAttribute' => ['verification_id', 'voted_by'], 'message' => 'The combination of Verification ID and Voted By has already been taken.'],
            [['verlock'], 'default', 'value' => '0'],
            [['verlock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'verification_id' => Yii::t('app', 'Verification ID'),
            'verification_type_id' => Yii::t('app', 'Verification Action ID'),
            'notes' => Yii::t('app', 'Notes'),
            'voted_by' => Yii::t('app', 'Voted By'),
            'voted_at' => Yii::t('app', 'Voted At'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
}
