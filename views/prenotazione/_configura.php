<?php

use kartik\select2\Select2;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\Prenotazione;
use app\models\RicercaModel;
use app\models\ServiziModel;
use yii\helpers\Html;
//use kartik\form\ActiveForm;
use yii\bootstrap4\ActiveForm;

//use yii\bootstrap\ActiveForm;
use dosamigos\selectize\SelectizeTextInput;
use app\models\Servizio;
use app\models\TipoCamera;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\daterange\DateRangePicker;
use yii\bootstrap4\Carousel;


/* @var $this yii\web\View */

$this->title = 'My Yii Application';
//var_dump($idT);die();
$url = \yii\helpers\Url::to(['getstanze']) . "&idAlbergo=" . $a->idAlbergo . "&idTipoCamera=" . $t->idTipoCamera;
?>
    <style>
        html {
            font-size: 14px;
        }

    </style>
    <div class="container">

        <div class="row">

            <div class="col-sm-5" style="height: 50vh; display: flex;flex-direction: column; justify-content: center">
                <!--                <img src="http://www.soloservizialberghi.it/wp-content/uploads/2015/09/Hotel-reception-bell-1170x601.jpg"-->
                <!--                     style="height: auto; width: 100%; vertical-align: central">-->
                <?php
                $urli = [];
                //                var_dump($imgs);die();
                if (isset($imgs) && !is_null($imgs)) {
                    foreach ($imgs as $value) {
                        $urli[$value->urlMedia] = [
                            'content' =>
                                '<img style="
                                            width: 95%;
                                            max-height: 10%;
                                            padding: 2.5%"
                                            src="' . $value->urlMedia . '">',
                        ];

                    }
                }
                if (count($urli) > 0) {

                    echo Carousel::widget([
                        'items' => array_values($urli),
                        'options' => []
                    ]);
                } else {
                    ?>
                    <img src="http://www.soloservizialberghi.it/wp-content/uploads/2015/09/Hotel-reception-bell-1170x601.jpg"
                         style="height: auto; width: 100%; vertical-align: central">

                <?php } ?>
            </div>
            <div class="col-sm-7 " style="height: 90vh">
                <h3> <?= Html::encode($t->nomeTipoCamera) ?> - <?= Html::encode($a->nomeAlbergo) ?> </h3>
                <div>
                    <?php $form = ActiveForm::begin([
                        'id' => 'form',
//                    'action' => ['paga'],
                        'options' => ['autocomplete' => 'off'],
                        'fieldConfig' => [
                            'horizontalCssClasses' => [
                                'label' => 'col-sm-2',
                                'offset' => 'col-sm-offset-4',
                                'wrapper' => 'col-sm-4',
                                'error' => '',
                                'hint' => '',
                            ],
                        ],
                    ]); ?>
                    <?= $form->field($model, 'DateVarie', [
                    ])->widget(DateRangePicker::classname(), [
                            'convertFormat' => true,
                            'useWithAddon' => false,
                            'pluginOptions' => [
                                'locale' => [
                                    'format' => 'Y-m-d',
                                    'separator' => ' to ',
                                ],
                                'opens' => 'left'
                            ]]
                    );
                    ?>
                    <?= $form->field($model, 'occupanti')->widget(Select2::classname(), [
                        'options' => [
                            'multiple' => false,
                            'placeholder' => "Seleziona il numero di occupanti"
                        ],
                        'pluginOptions' => [
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {A: (document.getElementById(\'prenotazione-arrivo\') !=null) ? document.getElementById("prenotazione-arrivo").value : 0,P: (document.getElementById(\'prenotazione-partenza\') !=null) ? document.getElementById("prenotazione-partenza").value : 0,F:document.getElementById("prenotazione-datevarie").value}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(city) { return city.text; }'),
                            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                        ],
                    ]); ?>
                    <?= $form->field($serv, 'S')->widget(Select2::classname(), [
                        'options' => [
                            'multiple' => true,
                            'placeholder' => "Seleziona i servizi da includere nella prenotazione"
                        ],
                        'data' => ArrayHelper::map($servizi, 'idServizio', 'nomeServizio'),

                    ]); ?>

                    <?= Html::submitButton('Prenota', ['class' => 'btn btn-primary ', 'name' => 'Ricerca-button']) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        console.debug(document.getElementById("prenotazione-arrivo").value);
    </script>

<?php








