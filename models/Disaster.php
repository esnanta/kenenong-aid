<?php

namespace app\models;

use Yii;
use \app\models\base\Disaster as BaseDisaster;

/**
 * This is the model class for table "disaster".
 */
class Disaster extends BaseDisaster
{
    // Disaster Types
    const TYPE_EARTHQUAKE = 1;
    const TYPE_FLOOD = 2;
    const TYPE_FIRE = 3;
    const TYPE_TSUNAMI = 4;
    const TYPE_VOLCANO = 5;
    const TYPE_LANDSLIDE = 6;
    const TYPE_OTHER = 99;

    // Disaster Statuses
    const STATUS_ACTIVE = 1;
    const STATUS_RESOLVED = 2;
    const STATUS_MONITORING = 3;

    /**
     * Get disaster types
     * @return array
     */
    public static function getDisasterTypes()
    {
        return [
            self::TYPE_EARTHQUAKE => 'Earthquake',
            self::TYPE_FLOOD => 'Flood',
            self::TYPE_FIRE => 'Fire',
            self::TYPE_TSUNAMI => 'Tsunami',
            self::TYPE_VOLCANO => 'Volcano',
            self::TYPE_LANDSLIDE => 'Landslide',
            self::TYPE_OTHER => 'Other',
        ];
    }

    /**
     * Get disaster statuses
     * @return array
     */
    public static function getDisasterStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_MONITORING => 'Monitoring',
        ];
    }

    /**
     * Get disaster type label
     * @return string
     */
    public function getDisasterTypeLabel()
    {
        $types = self::getDisasterTypes();
        return $types[$this->disaster_type] ?? 'Unknown';
    }

    /**
     * Get disaster status label
     * @return string
     */
    public function getDisasterStatusLabel()
    {
        $statuses = self::getDisasterStatuses();
        return $statuses[$this->disaster_status] ?? 'Unknown';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['disaster_type', 'disaster_status', 'start_date', 'description'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                // Set default values for new records
                if ($this->is_deleted === null) {
                    $this->is_deleted = 0;
                }
            }
            return true;
        }
        return false;
    }

}
