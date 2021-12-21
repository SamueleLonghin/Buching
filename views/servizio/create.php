<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Servizio */

$this->title = Yii::t('app', 'Create Servizio');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Servizios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servizio-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
