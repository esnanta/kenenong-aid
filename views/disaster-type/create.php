<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DisasterType */

$this->title = Yii::t('app', 'Create Disaster Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Disaster Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disaster-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
