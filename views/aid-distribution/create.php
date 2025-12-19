<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AidDistribution */

$this->title = Yii::t('app', 'Create Aid Distribution');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Aid Distribution'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aid-distribution-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
