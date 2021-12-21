<?php

use app\controllers\MediaController;
use yii\helpers\Html;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Media */

$this->title = Yii::t('app', 'Aggiungi foto');
//  var_dump($model->albergo);die();
$this->params['breadcrumbs'][] = ['label' => $model->albergo?$model->albergo->nomeAlbergo:'Albergo', 'url' => ['index','password'=>MediaController::$pass,'idAlbergo' => $model->idAlbergo]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-camera-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'up'=>$up,'data'=>$data
    ]) ?>

</div>
