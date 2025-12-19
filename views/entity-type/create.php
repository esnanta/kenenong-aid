<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntityType */

$this->title = Yii::t('app', 'Create Entity Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Entity Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
