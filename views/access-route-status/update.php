<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AccessRouteStatus */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Access Route Status',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Route Status'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="access-route-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
