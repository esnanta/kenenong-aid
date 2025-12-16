<?php

namespace app\models;

use Yii;
use \app\models\base\AidPlan as BaseAidPlan;

/**
 * This is the model class for table "t_aid_plan".
 */
class AidPlan extends BaseAidPlan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['shelter_id', 'plan_status', 'created_by', 'updated_by', 'is_deleted', 'deleted_by', 'verlock'], 'integer'],
            [['distribution_plan_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['remark'], 'string'],
            [['uuid'], 'string', 'max' => 36],
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
            'shelter_id' => Yii::t('app', 'Shelter ID'),
            'distribution_plan_date' => Yii::t('app', 'Distribution Plan Date'),
            'plan_status' => Yii::t('app', 'Plan Status'),
            'remark' => Yii::t('app', 'Remark'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
}
