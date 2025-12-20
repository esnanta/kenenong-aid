<?php

namespace app\models;

use Yii;
use \app\models\base\EntityType as BaseEntityType;

/**
 * This is the model class for table "t_entity_type".
 */
class EntityType extends BaseEntityType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 255],
            [['is_deleted'], 'integer'],
            [['uuid'], 'string', 'max' => 36],
            [['code'], 'unique'],
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
            'code' => Yii::t('app', 'Code'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
}
