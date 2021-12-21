<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoCamera */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-camera-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nomeTipoCamera')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descrizione')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
