<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MediaFile */

$this->title = Yii::t('app', 'Create Media File');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Media File'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-file-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
