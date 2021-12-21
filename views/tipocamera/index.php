<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipo Cameras';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-camera-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tipo Camera', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idTipoCamera',
            'nomeTipoCamera',
            'descrizione',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {paga}',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'buttons' => [


                    'view' => function ($url, $model) {
                        return Html::a('<i class="fa fa-eye"></i>', $url, [
                            'title' => Yii::t('app', 'view')
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => Yii::t('app', 'delete')
                        ]);
                    }, 'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => Yii::t('app', 'edit')
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>


</div>
