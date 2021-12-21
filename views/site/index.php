<?php

use kartik\select2\Select2;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\Albergo;
use app\models\RicercaModel;
use yii\helpers\Html;
//use kartik\form\ActiveForm; // or kartik\widgets\ActiveForm
use yii\bootstrap4\ActiveForm;
use dosamigos\selectize\SelectizeTextInput;
use app\models\Servizio;
use app\models\Camera;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;
use kartik\date\DatePicker;
use yii\bootstrap4\Carousel;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>




<?php
$form = ActiveForm::begin([
]);
?>


<div class="container">
    <div class="row stiky-top" style="margin-top: 10px">
        <?= $form->field($model, 'q', [
            'inputOptions' => [
                'placeholder' => $model->getAttributeLabel('q'),
            ],
            'options' => ['class' => ' col-8 ']
        ])->label(false) ?>


        <?= $form->field($model, 'occupanti', ['options' => ['class' => '  col-2 ',], 'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('occupanti'),
        ],])->textInput([
            'type' => 'number'
        ])->label(false) ?>
        <div class="col-2">
            <?= Html::submitButton('Cerca', ['class' => 'btn btn-primary col-12', 'name' => 'Ricerca-button']) ?>
        </div>
        <!-- $form->field($model, 'date', [-->
        <!--    'addon' => ['prepend' => ['content' => '<i class="fas fa-calendar-alt"></i>']],-->
        <!--    'options' => ['class' => 'drp-container form-group col-sm-6 col-md-6 ',],-->
        <!--])->widget(DateRangePicker::classname(), [-->
        <!--    'useWithAddon' => true,-->
        <!--    'pluginOptions' => [-->
        <!--        'locale' => [-->
        <!--            'format' => 'Y-m-d',-->
        <!--            'separator' => ' -> ',-->
        <!--        ]-->
        <!--    ]-->
        <!--])->label(false);-->
        <!--?>-->

        <?php ActiveForm::end(); ?>
    </div>
    <?php
    if (isset($alb)) {
        foreach ($alb as $al) {
            ?>
            <div class="card" style="margin-top: 10px;height:8rem;">
                <a class="stretched-link"
                   href="<?= Url::toRoute(['prenotazione/prenota', 'idAlbergo' => isset($al['idAlbergo']) ? $al['idAlbergo'] : $al['idAlbergo'], 'idTipoCamera' => $al['idTipoCamera']]); ?>"
                >
                </a>
                <div class="row">
                    <div class="col-3">
                        <!--                        <img style="width: 95%; padding: 2.5%"-->
                        <!--                             src="-->
                        <!--                        -->
                        <?//= isset($al['urli']) && !is_null($al['urli']) ? $al['urli'] : 'http://www.soloservizialberghi.it/wp-content/uploads/2015/09/Hotel-reception-bell-1170x601.jpg' ?><!--">-->
                        <?php
                        $urli = [];
                        if (isset($al['urli']) && !is_null($al['urli'])) {
                            foreach (explode(',', $al['urli']) as $value) {
                                if (trim($value) != "")
                                    $urli[$value] = [
                                        'content' =>
                                            '<img style="
                                            width: 95%;
                                            max-height: 10%;
                                            padding: 2.5%"
                                            src="' . $value . '">',
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
                            <img style="
                            width: 95%;
                            height: auto;
                            padding: 2.5%" ;

                                 src="<?= 'http://www.soloservizialberghi.it/wp-content/uploads/2015/09/Hotel-reception-bell-1170x601.jpg' ?>"
                            >

                        <?php } ?>
                    </div>
                    <div class="col-4">

                        <h3><?= Html::encode($al['nomeTipoCamera']) ?> - <?= Html::encode($al['nomeAlbergo']) ?></h3>
                        <h4><?= Html::encode($al['indirizzoAlbergo']) ?></h4>


                        <h6>Disponibilità: <?= isset($al['quante']) ? Html::encode($al['quante']) : "" ?></h6>

                    </div>
                    <div class="col-3">
                        <?php
                        if (isset($al['servizi']))
                            foreach (explode(',', $al['servizi']) as $item) {
                                ?>
                                <button type="button" class="btn btn-outline-info"><?= Html::encode($item) ?></button>
                                <?php
                            }
                        ?>
                        <div>

                            <?php
                            //                        if (isset($al['postiletto']))
                            //                            echo $al['postiletto'];
                            $p = [];
                            foreach (explode(',', $al['postiletto']) as $v) {
                                $p[$v] = key_exists($v, $p) ? $p[$v] + 1 : 1;
                            }
                            foreach ($p as $v => $value) {
                                ?>
                                <button type="button" class="btn">
                                    <?php
                                    echo Html::encode($value).'x';
                                    for ($i = 0; $i < $v; $i++) {
                                        ?>
                                        <i class="fa fa-user"></i>
                                        <?php
                                    }
                                    ?>
                                </button>
                                <?php
                            }
//                            var_dump($p);
//                            die();
                            ?>
                        </div>
                    </div>
                    <div class="col-2">
                        <h6 style="font-size: xx-large; text-align: center; vertical-align: center"> <?= isset($al['costoTipoCamera']) ? Html::encode($al['costoTipoCamera']) : "" ?>
                            €</h6>
                    </div>
                </div>
            </div>

            <?php
        }
    }

    ?>
</div>

