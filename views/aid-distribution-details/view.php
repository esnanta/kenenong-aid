<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\AidDistributionDetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Aid Distribution Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aid-distribution-details-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Aid Distribution Details').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'aidDistribution.id',
            'label' => Yii::t('app', 'Aid Distribution'),
        ],
        [
            'attribute' => 'aidItem.title',
            'label' => Yii::t('app', 'Aid Item'),
        ],
        'quantity',
        [
            'attribute' => 'unit.title',
            'label' => Yii::t('app', 'Unit'),
        ],
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
        <h4>AidItems<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnAidItems = [
        ['attribute' => 'id', 'visible' => false],
        'aid_category',
        'title',
        'unit',
        'description',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->aidItem,
        'attributes' => $gridColumnAidItems    ]);
    ?>
    <div class="row">
        <h4>AidDistribution<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnAidDistribution = [
        ['attribute' => 'id', 'visible' => false],
        'aid_plan_id',
        'shelter_id',
        'distribution_date',
        'distributed_by',
        'notes',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->aidDistribution,
        'attributes' => $gridColumnAidDistribution    ]);
    ?>
    <div class="row">
        <h4>Units<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUnits = [
        ['attribute' => 'id', 'visible' => false],
        'code',
        'title',
        'description',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->unit,
        'attributes' => $gridColumnUnits    ]);
    ?>
</div>
