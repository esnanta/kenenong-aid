<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\AidPlanDetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Aid Plan Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aid-plan-details-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Aid Plan Details').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'aidPlan.id',
            'label' => Yii::t('app', 'Aid Plan'),
        ],
        [
            'attribute' => 'item.title',
            'label' => Yii::t('app', 'Item'),
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
        <h4>Item<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnItem = [
        ['attribute' => 'id', 'visible' => false],
        'item_category_id',
        'title',
        'unit',
        'description',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->item,
        'attributes' => $gridColumnItem    ]);
    ?>
    <div class="row">
        <h4>AidPlan<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnAidPlan = [
        ['attribute' => 'id', 'visible' => false],
        'shelter_id',
        'distribution_plan_date',
        'plan_status',
        'remark',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->aidPlan,
        'attributes' => $gridColumnAidPlan    ]);
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
