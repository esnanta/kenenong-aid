<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\VerificationVote */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Verification Vote'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verification-vote-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Verification Vote').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'verification.id',
            'label' => Yii::t('app', 'Verification'),
        ],
        [
            'attribute' => 'verificationAction.title',
            'label' => Yii::t('app', 'Verification Action'),
        ],
        'notes:ntext',
        [
            'attribute' => 'votedBy.name',
            'label' => Yii::t('app', 'Voted By'),
        ],
        'voted_at',
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
        <h4>VerificationAction<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnVerificationAction = [
        ['attribute' => 'id', 'visible' => false],
        'code',
        'title',
        'weight',
        'description',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->verificationAction,
        'attributes' => $gridColumnVerificationAction    ]);
    ?>
    <div class="row">
        <h4>Verification<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnVerification = [
        ['attribute' => 'id', 'visible' => false],
        'entity_type_id',
        'entity_id',
        'last_activity_at',
        'is_deleted',
        ['attribute' => 'verlock', 'visible' => false],
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->verification,
        'attributes' => $gridColumnVerification    ]);
    ?>
    <div class="row">
        <h4>Users<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUsers = [
        ['attribute' => 'id', 'visible' => false],
        'name',
        'email',
        'email_verified_at',
                'remember_token',
        'current_team_id',
        'profile_photo_path',
        ['attribute' => 'uuid', 'visible' => false],
    ];
    echo DetailView::widget([
        'model' => $model->votedBy,
        'attributes' => $gridColumnUsers    ]);
    ?>
</div>
