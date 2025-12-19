<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AccessRouteStatus */

$this->title = Yii::t('app', 'Create Access Route Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Route Status'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-route-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
