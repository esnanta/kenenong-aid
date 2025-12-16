<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AccessRouteShelters */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Access Route Shelters',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Route Shelters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="access-route-shelters-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
