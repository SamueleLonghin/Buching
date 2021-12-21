<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Servizio */

$this->title = Yii::t('app', 'Update Servizio: {name}', [
    'name' => $model->idServizio,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Servizios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idServizio, 'url' => ['view', 'id' => $model->idServizio]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="servizio-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
