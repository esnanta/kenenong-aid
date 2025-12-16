<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VerificationAction */

$this->title = Yii::t('app', 'Create Verification Action');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Verification Action'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verification-action-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
