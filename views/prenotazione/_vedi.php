<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Prenotazione */

$this->title = $model->nomePrenotazione;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Prenotaziones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="prenotazione-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'arrivo',
            'partenza',
            'occupanti',
            [
                'label' => 'Albergo',
                'value' => function ($model) {
                    return $model->camera->albergo->nomeAlbergo;
                }
            ],
            'idCamera',
            'costoPrenotazione'
        ],
    ]) ?>


    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['prenota', 'id' => $model->idPrenotazione, 'forza' => true], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->idPrenotazione], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'Conferma'), ['', 'id' => $model->idPrenotazione, 'conferma' => true], ['class' => 'btn btn-success']) ?>
    </p>
</div>
