<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\AidPlan */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Aid Plan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aid-plan-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Aid Plan').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'shelter.title',
            'label' => Yii::t('app', 'Shelter'),
        ],
        'distribution_plan_date',
        'plan_status',
        'remark:ntext',
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
if($providerAidDistribution->totalCount){
    $gridColumnAidDistribution = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
                        [
                'attribute' => 'shelter.title',
                'label' => Yii::t('app', 'Shelter')
            ],
            'distribution_date',
            [
                'attribute' => 'distributedBy.name',
                'label' => Yii::t('app', 'Distributed By')
            ],
            'notes:ntext',
            'is_deleted',
            ['attribute' => 'verlock', 'visible' => false],
            ['attribute' => 'uuid', 'visible' => false],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerAidDistribution,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-t-aid-distribution']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Aid Distribution')),
        ],
        'export' => false,
        'columns' => $gridColumnAidDistribution
    ]);
}
?>

    </div>
    <div class="row">
        <h4>Shelter<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnShelter = [
        ['attribute' => 'id', 'visible' => false],
        'disaster_id',
        'title',
        'latitude',
        'longitude',
        'evacuee_count',
        'aid_status',
        'last_aid_distribution_at',
        'description',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->shelter,
        'attributes' => $gridColumnShelter    ]);
    ?>
    
    <div class="row">
<?php
if($providerAidPlanDetails->totalCount){
    $gridColumnAidPlanDetails = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
                        [
                'attribute' => 'item.title',
                'label' => Yii::t('app', 'Item')
            ],
            'quantity',
            [
                'attribute' => 'unit.title',
                'label' => Yii::t('app', 'Unit')
            ],
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
