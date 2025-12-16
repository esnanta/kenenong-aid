<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\MediaFile */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Media File'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-file-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Media File').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'entityType.title',
            'label' => Yii::t('app', 'Entity Type'),
        ],
        'entity_id',
        'file_path',
        'notes',
        'file_type',
        'mime_type',
        'taken_at',
        'uploaded_by',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    <div class="row">
        <h4>EntityType<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnEntityType = [
        ['attribute' => 'id', 'visible' => false],
        'code',
        'title',
        'description',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->entityType,
        'attributes' => $gridColumnEntityType    ]);
    ?>
</div>
