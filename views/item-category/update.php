<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItemCategory */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Item Category',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="item-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
