<?php

namespace app\models;

use Yii;
use \app\models\base\AidPlanDetails as BaseAidPlanDetails;

/**
 * This is the model class for table "t_aid_plan_details".
 */
class AidPlanDetails extends BaseAidPlanDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['aid_plan_id', 'item_id', 'quantity', 'unit_id', 'verlock'], 'integer'],
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
            'aid_plan_id' => Yii::t('app', 'Aid Plan ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
}
