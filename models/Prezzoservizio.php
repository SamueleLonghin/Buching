<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prezzoservizio".
 *
 * @property int $idStagione
 * @property int $idServizio
 * @property float|null $costo
 *
 * @property Servizio $idServizio0
 * @property Stagione $idStagione0
 */
class Prezzoservizio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prezzoservizio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idStagione', 'idServizio'], 'required'],
            [['idStagione', 'idServizio'], 'integer'],
            [['costo'], 'number'],
            [['idStagione', 'idServizio'], 'unique', 'targetAttribute' => ['idStagione', 'idServizio']],
            [['idServizio'], 'exist', 'skipOnError' => true, 'targetClass' => Servizio::className(), 'targetAttribute' => ['idServizio' => 'idServizio']],
            [['idStagione'], 'exist', 'skipOnError' => true, 'targetClass' => Stagione::className(), 'targetAttribute' => ['idStagione' => 'idStagione']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idStagione' => Yii::t('app', 'Id Stagione'),
            'idServizio' => Yii::t('app', 'Id Servizio'),
            'costo' => Yii::t('app', 'Costo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdServizio0()
    {
        return $this->hasOne(Servizio::className(), ['idServizio' => 'idServizio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdStagione0()
    {
        return $this->hasOne(Stagione::className(), ['idStagione' => 'idStagione']);
    }
}
