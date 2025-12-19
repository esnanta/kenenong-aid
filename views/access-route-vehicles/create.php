<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AccessRouteVehicles */

$this->title = Yii::t('app', 'Create Access Route Vehicles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Route Vehicles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-route-vehicles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
