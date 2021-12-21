<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Prenotazione */

$this->title = Yii::t('app', 'Create Prenotazione');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Prenotaziones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prenotazione-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
