<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VerificationVote */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Verification Vote',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Verification Vote'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="verification-vote-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
