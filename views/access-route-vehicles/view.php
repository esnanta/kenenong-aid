<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\AccessRouteVehicles */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Route Vehicles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-route-vehicles-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Access Route Vehicles').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'accessRoute.id',
            'label' => Yii::t('app', 'Access Route'),
        ],
        [
            'attribute' => 'vehicleType.title',
            'label' => Yii::t('app', 'Vehicle Type'),
        ],
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
        <h4>AccessRoute<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnAccessRoute = [
        ['attribute' => 'id', 'visible' => false],
        'disaster_id',
        'route_name',
        'route_geometry',
        'route_length_km',
        'access_route_status_id',
        'geometry_updated_at',
        'description',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->accessRoute,
        'attributes' => $gridColumnAccessRoute    ]);
    ?>
    <div class="row">
        <h4>VehicleTypes<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnVehicleTypes = [
        ['attribute' => 'id', 'visible' => false],
        'code',
        'title',
        'description',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->vehicleType,
        'attributes' => $gridColumnVehicleTypes    ]);
    ?>
</div>
