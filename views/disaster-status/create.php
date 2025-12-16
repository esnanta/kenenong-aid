<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DisasterStatus */

$this->title = Yii::t('app', 'Create Disaster Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Disaster Status'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disaster-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
