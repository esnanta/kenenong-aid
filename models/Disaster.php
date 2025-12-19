<?php

namespace app\models;

use app\models\base\Disaster as BaseDisaster;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_disaster".
 */
class Disaster extends BaseDisaster
{
    /**
     * @inheritdoc
     */
    public function rules(): array
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

    public function getDisasterTypeLabel(): string
    {
        return $this->disasterType ? $this->disasterType->title : 'Unknown';
    }

    public function getDisasterStatusLabel(): string
    {
        return $this->disasterStatus ? $this->disasterStatus->title : 'Unknown';
    }

    public static function getDisasterTypes(): array
    {
        return ArrayHelper::map(DisasterType::find()->where(['is_deleted' => 0])->all(), 'id', 'title');
    }

    public static function getDisasterStatuses(): array
    {
        return ArrayHelper::map(DisasterStatus::find()->where(['is_deleted' => 0])->all(), 'id', 'title');
    }
	
}
