<?php

namespace app\models;

use Yii;
use \app\models\base\Verification as BaseVerification;

/**
 * This is the model class for table "t_verification".
 */
class Verification extends BaseVerification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['entity_type_id', 'entity_id', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['created_at', 'updated_at', 'last_activity_at', 'deleted_at'], 'safe'],
            [['is_deleted'], 'integer'],
            [['uuid'], 'string', 'max' => 36],
            [['entity_type_id', 'entity_id'], 'unique', 'targetAttribute' => ['entity_type_id', 'entity_id'], 'message' => 'The combination of Entity Type ID and Entity ID has already been taken.'],
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
            'entity_type_id' => Yii::t('app', 'Entity Type ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'last_activity_at' => Yii::t('app', 'Last Activity At'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
}
