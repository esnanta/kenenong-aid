<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Unit */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Unit'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Unit').' '. Html::encode($this->title) ?></h2>
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
if($providerAidDistributionDetails->totalCount){
    $gridColumnAidDistributionDetails = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'aidDistribution.id',
                'label' => Yii::t('app', 'Aid Distribution')
            ],
            [
                'attribute' => 'item.title',
                'label' => Yii::t('app', 'Item')
            ],
            'quantity',
                        ['attribute' => 'verlock', 'visible' => false],
            ['attribute' => 'uuid', 'visible' => false],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerAidDistributionDetails,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-t-aid-distribution-details']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Aid Distribution Details')),
        ],
        'export' => false,
        'columns' => $gridColumnAidDistributionDetails
    ]);
}
?>

    </div>
    
    <div class="row">
<?php
if($providerAidPlanDetails->totalCount){
    $gridColumnAidPlanDetails = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'aidPlan.id',
                'label' => Yii::t('app', 'Aid Plan')
            ],
            [
                'attribute' => 'item.title',
                'label' => Yii::t('app', 'Item')
            ],
            'quantity',
                        ['attribute' => 'verlock', 'visible' => false],
            ['attribute' => 'uuid', 'visible' => false],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerAidPlanDetails,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-t-aid-plan-details']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Aid Plan Details')),
        ],
        'export' => false,
        'columns' => $gridColumnAidPlanDetails
    ]);
}
?>

    </div>
</div>
