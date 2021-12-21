<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Media');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Media'), ['create', 'idAlbergo' => $idAlbergo], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'idMedia',
            'urlMedia',
            [

                'attribute' => 'img',

                'format' => 'html',

                'label' => 'Anteprima',

                'value' => function ($data) {

                    return Html::img($data['urlMedia'],

                        ['width' => '100px']);

                },

            ],
            'descrizioneMedia',
            'idTipoCamera',
            'idAlbergo',

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
                    // 'delete' => function ($url, $model) {
                    //     return Html::a('<i class="fas fa-trash"></i>', $url, [
                    //         'title' => Yii::t('app', 'delete')
                    //     ]);
                    // },
                     'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => Yii::t('app', 'edit')
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>


</div>
