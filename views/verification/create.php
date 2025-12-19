<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Verification */

$this->title = Yii::t('app', 'Create Verification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Verification'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
