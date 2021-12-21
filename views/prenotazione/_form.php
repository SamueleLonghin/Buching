<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Prenotazione */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prenotazione-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'arrivo')->textInput() ?>

    <?= $form->field($model, 'partenza')->textInput() ?>

    <?= $form->field($model, 'occupanti')->textInput() ?>

    <?= $form->field($model, 'Note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'idCliente')->textInput() ?>

    <?= $form->field($model, 'idCamera')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
