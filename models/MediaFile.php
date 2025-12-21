<?php

namespace app\models;

use Yii;
use \app\models\base\MediaFile as BaseMediaFile;

/**
 * This is the model class for table "t_media_file".
 */
class MediaFile extends BaseMediaFile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['entity_type_id', 'entity_id', 'uploaded_by', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['taken_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['file_path'], 'string', 'max' => 500],
            [['notes'], 'string', 'max' => 255],
            [['file_type'], 'string', 'max' => 50],
            [['mime_type'], 'string', 'max' => 100],
            [['is_deleted'], 'integer'],
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
            'entity_type_id' => Yii::t('app', 'Entity Type ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'file_path' => Yii::t('app', 'File Path'),
            'notes' => Yii::t('app', 'Notes'),
            'file_type' => Yii::t('app', 'File Type'),
            'mime_type' => Yii::t('app', 'Mime Type'),
            'taken_at' => Yii::t('app', 'Taken At'),
            'uploaded_by' => Yii::t('app', 'Uploaded By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'verlock' => Yii::t('app', 'Verlock'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }
}
