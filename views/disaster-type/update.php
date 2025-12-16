<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DisasterType */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Disaster Type',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Disaster Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="disaster-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
