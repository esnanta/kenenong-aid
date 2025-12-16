<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Shelter */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shelter'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shelter-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Shelter').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'disaster.title',
            'label' => Yii::t('app', 'Disaster'),
        ],
        'title',
        'latitude',
        'longitude',
        'evacuee_count',
        'aid_status',
        'last_aid_distribution_at',
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
if($providerAccessRouteShelters->totalCount){
    $gridColumnAccessRouteShelters = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'accessRoute.id',
                'label' => Yii::t('app', 'Access Route')
            ],
                        'is_deleted',
            ['attribute' => 'verlock', 'visible' => false],
            ['attribute' => 'uuid', 'visible' => false],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerAccessRouteShelters,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-t-access-route-shelters']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Access Route Shelters')),
        ],
        'export' => false,
        'columns' => $gridColumnAccessRouteShelters
    ]);
}
?>

    </div>
    
    <div class="row">
<?php
if($providerAidDistribution->totalCount){
    $gridColumnAidDistribution = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'aidPlan.id',
                'label' => Yii::t('app', 'Aid Plan')
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
<?php
if($providerAidPlan->totalCount){
    $gridColumnAidPlan = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
                        'distribution_plan_date',
            'plan_status',
            'remark:ntext',
            'is_deleted',
            ['attribute' => 'verlock', 'visible' => false],
            ['attribute' => 'uuid', 'visible' => false],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerAidPlan,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-t-aid-plan']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Aid Plan')),
        ],
        'export' => false,
        'columns' => $gridColumnAidPlan
    ]);
}
?>

    </div>
    <div class="row">
        <h4>Disaster<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnDisaster = [
        ['attribute' => 'id', 'visible' => false],
        'title',
        'disaster_type_id',
        'disaster_status_id',
        'start_date',
        'end_date',
        'description:ntext',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->disaster,
        'attributes' => $gridColumnDisaster    ]);
    ?>
</div>
