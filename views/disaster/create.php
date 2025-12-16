<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Disaster */

$this->title = Yii::t('app', 'Create Disaster');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Disaster'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disaster-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
