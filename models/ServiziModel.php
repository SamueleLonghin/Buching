<?php

namespace app\models;
use Yii;
use yii\base\Model;

class ServiziModel extends Model
{
    public $S =[];
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['S'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'S' => Yii::t('app', 'Servizi'),
        ];
    }

}
