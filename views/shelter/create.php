<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Shelter */

$this->title = Yii::t('app', 'Create Shelter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shelter'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shelter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
