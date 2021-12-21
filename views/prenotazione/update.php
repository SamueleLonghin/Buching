<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Prenotazione */

$this->title = Yii::t('app', 'Update Prenotazione: {name}', [
    'name' => $model->idPrenotazione,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Prenotaziones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idPrenotazione, 'url' => ['view', 'id' => $model->idPrenotazione]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="prenotazione-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
