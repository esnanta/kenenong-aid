<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\VerificationAction */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Verification Action'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verification-action-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Verification Action').' '. Html::encode($this->title) ?></h2>
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
        'weight',
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
if($providerVerificationVote->totalCount){
    $gridColumnVerificationVote = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'verification.id',
                'label' => Yii::t('app', 'Verification')
            ],
                        'notes:ntext',
            [
                'attribute' => 'votedBy.name',
                'label' => Yii::t('app', 'Voted By')
            ],
            'voted_at',
            'is_deleted',
            ['attribute' => 'verlock', 'visible' => false],
            ['attribute' => 'uuid', 'visible' => false],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerVerificationVote,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-t-verification-vote']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Verification Vote')),
        ],
        'export' => false,
        'columns' => $gridColumnVerificationVote
    ]);
}
?>

    </div>
</div>
