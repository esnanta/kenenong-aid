<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\AccessRouteStatus */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Route Status'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-route-status-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Access Route Status').' '. Html::encode($this->title) ?></h2>
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
if($providerAccessRoute->totalCount){
    $gridColumnAccessRoute = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'disaster.title',
                'label' => Yii::t('app', 'Disaster')
            ],
            'route_name',
            'route_geometry',
            'route_length_km',
                        'geometry_updated_at',
            'description:ntext',
            'is_deleted',
            ['attribute' => 'verlock', 'visible' => false],
            ['attribute' => 'uuid', 'visible' => false],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerAccessRoute,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-t-access-route']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Access Route')),
        ],
        'export' => false,
        'columns' => $gridColumnAccessRoute
    ]);
}
?>

    </div>
</div>
