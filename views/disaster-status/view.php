<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\DisasterStatus */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Disaster Status'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disaster-status-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Disaster Status').' '. Html::encode($this->title) ?></h2>
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
        'code',
        'title',
        'description:ntext',
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
<?php
if($providerDisaster->totalCount){
    $gridColumnDisaster = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            'title',
            [
                'attribute' => 'disasterType.title',
                'label' => Yii::t('app', 'Disaster Type')
            ],
                        'start_date',
            'end_date',
            'description:ntext',
            'is_deleted',
            ['attribute' => 'verlock', 'visible' => false],
            ['attribute' => 'uuid', 'visible' => false],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerDisaster,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-t-disaster']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Disaster')),
        ],
        'export' => false,
        'columns' => $gridColumnDisaster
    ]);
}
?>

    </div>
</div>
