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


/* @var $this yii\web\View */

$this->title = 'Prenotazione';
//var_dump($idT);die();
?>
<!--    <div class="card">-->
        <?php
//        var_dump($model);die();
        switch ($model->statoPrenotazione) {
            case 'CREATA':
                echo $this->render('_vedi', [
                    'model' => $model, 'a' => $a, 't' => $t, 'serv' => $serv, 'servizi' => $servizi
                ]);
                break;
            case 'CONFERMATA': break;
            default:  echo $this->render('_configura', [
                'model' => $model, 'a' => $a, 't' => $t, 'serv' => $serv, 'servizi' => $servizi, 'imgs'=>$imgs
            ]);
                break;;
        } ?>
<!--        <div class="row justify-content-md-center">-->
<!--            <div class="justify-content-md-center">-->
<!--                <h4>Scegli</h4>-->
<!--                <div class="col-sm-2 btn btn-info" style="width: 2rem; height: 2rem; border-radius: 50%;">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="justify-content-md-center">-->
<!---->
<!--                <h4>Configura</h4>-->
<!--                <div class="col-sm-2 btn btn-info" style="width: 2rem; height: 2rem; border-radius: 50%;">-->
<!---->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="justify-content-md-center">-->
<!--                <h4>Conferma</h4>-->
<!--                <div class="col-sm-2 btn btn-secondary align-self-center" style="width: 2rem; height: 2rem; border-radius: 50%;"></div>-->
<!--            </div>-->
<!---->
<!--        </div>-->
<!--    </div>-->










