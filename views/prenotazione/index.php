<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Prenotazioni');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prenotazione-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'idPrenotazione',
            'arrivo',
            'partenza',
            'occupanti',
            'Note:ntext',
            'nomeServizi',
//            'nomeCliente',
            'nomeCamera',
            'nomeTipoCamera',
            'statoPrenotazione',
            'costoPrenotazione',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'buttons' => [

                    'paga' => function ($url, $model) {
                        return Html::a('<i class="fa fa-info"></i>', $url, [
                            'title' => Yii::t('app', 'info')
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fa fa-eye"></i>', $url, [
                            'title' => Yii::t('app', 'prenota')
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', Url::toRoute(['prenotazione/prenota', 'id' => $model->idPrenotazione,'forza' => true]), [
                            'title' => Yii::t('app', 'edit')
                        ]);
                    },

                    // 'delete' => function ($url, $model) {
                    //     return Html::a('<i class="fas fa-trash"></i>', $url, [
                    //         'title' => Yii::t('app', 'delete')
                    //     ]);
                    // }, 

                ],
            ],
        ],
    ]); ?>


</div>
