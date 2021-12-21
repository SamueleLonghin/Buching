<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\file\FileInput;
use yii\bootstrap4\BootstrapAsset;
use kartik\select2\Select2;
use app\models\TipoCamera;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $model app\models\Media */
/* @var $form yii\widgets\ActiveForm */

$url = \yii\helpers\Url::to(['gettipi']);

?>

<div class="media-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
//            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-3',
                'offset' => 'col-sm-offset-3',
                'wrapper' => 'col-sm-6',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]); ?>

    <!--    <? //= $form->field($model, 'urlMedia')->textInput(['maxlength' => true]) ?>-->

    <?= $form->field($up, 'media')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'initialPreview' => is_null($model->urlMedia) ? "http://www.soloservizialberghi.it/wp-content/uploads/2015/09/Hotel-reception-bell-1170x601.jpg" : $model->urlMedia,
            'showPreview' => true,
            'initialPreviewAsData' => true,
            'maxFileSize' => 2800,
            'overwriteInitial' => true,

        ]


    ]) ?>
    <?= $form->field($model, 'descrizioneMedia')->textInput(['maxlength' => true]) ?>

    <!--    <? //= $form->field($model, 'idTipoCamera') ?>-->

    <?= $form->field($model, 'idTipoCamera')->widget(Select2::classname(), [
        'options' => [
            'placeholder' => "Seleziona i servizi da includere nella prenotazione"
        ],
        'data' => ArrayHelper::map($data, 'idTipoCamera', 'nomeTipoCamera'),

    ]); ?>

    <?= $form->field($model, 'idAlbergo')->textInput()->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
