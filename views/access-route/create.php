<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AccessRoute */

$this->title = Yii::t('app', 'Create Access Route');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Route'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-route-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
