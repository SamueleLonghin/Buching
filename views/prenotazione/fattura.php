<?php

use Yii;
use yii\helpers\Html;

?>
<div style="width: 29cm; max-height: 21cm">

    <h1>Fattura non legale</h1>
    <table>

        <tr>
            <td>
                Il signor
            </td>
            <td>
                <?= Html::encode($model->cliente->nome) ?>
            </td>
        </tr>
        <tr>
            <td>
                Cellulare
            </td>
            <td>
                <?= Html::encode($model->cliente->telefono) ?>
            </td>
        </tr>
        <tr>
            <td>
                Email
            </td>
            <td>
                <?= Html::encode($model->cliente->email) ?>
            </td>
        </tr>
        <tr>
            <td>
                Presso
            </td>
            <td>
                <?= Html::encode($model->camera->albergo->nomeAlbergo) ?>
            </td>
        </tr>
        <tr>
            <td>
                via
            </td>
            <td>
                <?= Html::encode($model->camera->albergo->indirizzoAlbergo) ?>
            </td>
        </tr>
        <tr>
            <td>
                Camera
            </td>
            <td>
                <?= Html::encode($model->camera->tipoCamera->nomeTipoCamera) ?>
            </td>
        </tr>
        <tr>
            <td>
                Numero Occupanti
            </td>
            <td>
                <?= Html::encode($model->occupanti) ?>
            </td>
        </tr>
        <tr>
            <td>
                Numero Notti
            </td>
            <td>
                <?= Html::encode(date_diff(new \DateTime($model->partenza), new \DateTime($model->arrivo))->days) ?>
            </td>
        </tr>
        <tr>
            <td>
                Arrivo
            </td>
            <td>
                <?= Html::encode($model->arrivo) ?>
            </td>
        </tr>
        <tr>
            <td>
                Partenza
            </td>
            <td>
                <?= Html::encode($model->partenza) ?>
            </td>
        </tr>
        <tr>
            <td>
                Servizi
            </td>
            <td>
                <?= Html::encode(implode(', ',array_column($model->servizi,'nomeServizio'))) ?>
            </td>
        </tr>
        <tr>

            <td>
                Costo Camera per persona
            </td>
            <td>
                <?php
                $costototC = 0;
                foreach ($model->getGiorniStagioneCamera() as $costo => $quanti) {
                    $costototC += $costo * $quanti
                    ?>

                    <p>
                        <?= Html::encode($quanti . ' x ' . $costo . '€') ?>
                    </p>
                <?php } ?>
                <?="Tot:". Html::encode($costototC) ?>
            </td>
        </tr> <tr>

            <td>
                Costo Camere
            </td>
            <td>
                <?=Html::encode($costototC * $model->occupanti) ?>
            </td>
        </tr>
        <tr>

            <td>
                Costo Servizi per persona
            </td>
            <td>
                <?php
                $costototS = 0;
                foreach ($model->getGiorniStagioneServizi() as $costo => $quanti) {
                    $costototS += $costo * $quanti
                    ?>

                    <p>
                        <?= Html::encode($quanti . ' x ' . $costo . '€') ?>
                    </p>
                <?php } ?>
                <?="Tot:". Html::encode($costototS) ?>
            </td>
        </tr> <tr>

            <td>
                Costo Servizi
            </td>
            <td>
                <?=Html::encode($costototS * $model->occupanti) ?>
            </td>
        </tr>
        <tr>
            <td>
                Costo Totale
            </td>
            <td>
                <?= Html::encode(($costototC + $costototS)* $model->occupanti) ?>
            </td>
        </tr>
    </table>
</div>
<style>
    table {
        border-spacing: 5px;
        width: 100%;
    }

    th {
        text-align: left;
    }

    th, td {
        padding: 15px;
    }

    table, th, td {
        border: 1px solid black;
    }
</style>