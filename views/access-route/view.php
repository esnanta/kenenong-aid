<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\AccessRoute */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Route'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-route-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Access Route').' '. Html::encode($this->title) ?></h2>
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
        'route_name',
        'route_geometry',
        'route_length_km',
        [
            'attribute' => 'accessRouteStatus.title',
            'label' => Yii::t('app', 'Access Route Status'),
        ],
        'geometry_updated_at',
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
    <div class="row">
        <h4>AccessRouteStatus<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnAccessRouteStatus = [
        ['attribute' => 'id', 'visible' => false],
        'code',
        'title',
        'description:ntext',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->accessRouteStatus,
        'attributes' => $gridColumnAccessRouteStatus    ]);
    ?>
    
    <div class="row">
<?php
if($providerAccessRouteShelters->totalCount){
    $gridColumnAccessRouteShelters = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
                        [
                'attribute' => 'shelter.title',
                'label' => Yii::t('app', 'Shelter')
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
if($providerAccessRouteVehicles->totalCount){
    $gridColumnAccessRouteVehicles = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
                        [
                'attribute' => 'vehicleType.title',
                'label' => Yii::t('app', 'Vehicle Type')
            ],
            'is_deleted',
            ['attribute' => 'verlock', 'visible' => false],
            ['attribute' => 'uuid', 'visible' => false],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerAccessRouteVehicles,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-t-access-route-vehicles']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Access Route Vehicles')),
        ],
        'export' => false,
        'columns' => $gridColumnAccessRouteVehicles
    ]);
}
?>

    </div>
</div>
