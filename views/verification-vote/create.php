<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VerificationVote */

$this->title = Yii::t('app', 'Create Verification Vote');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Verification Vote'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verification-vote-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
