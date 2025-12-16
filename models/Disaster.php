<?php

namespace app\models;

use Yii;
use \app\models\base\Disaster as BaseDisaster;

/**
 * This is the model class for table "t_disaster".
 */
class Disaster extends BaseDisaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['disaster_type_id', 'disaster_status_id', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['is_deleted'], 'string', 'max' => 1],
            [['uuid'], 'string', 'max' => 36],
            [['verlock'], 'default', 'value' => '0'],
            [['verlock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	
}
