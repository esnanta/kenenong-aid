<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AidPlan */

$this->title = Yii::t('app', 'Create Aid Plan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Aid Plan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aid-plan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
