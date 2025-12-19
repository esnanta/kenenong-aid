<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AccessRouteShelters */

$this->title = Yii::t('app', 'Create Access Route Shelters');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Route Shelters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-route-shelters-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
