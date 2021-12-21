<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoCamera */

$this->title = 'Update Tipo Camera: ' . $model->idTipoCamera;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Cameras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idTipoCamera, 'url' => ['view', 'id' => $model->idTipoCamera]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipo-camera-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
