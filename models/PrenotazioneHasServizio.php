<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prenotazione_has_servizio".
 *
 * @property int $idPrenotazione
 * @property int $idServizio
 *
 * @property Prenotazione $idPrenotazione0
 * @property Servizio $idServizio0
 */
class PrenotazioneHasServizio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prenotazione_has_servizio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idPrenotazione', 'idServizio'], 'required'],
            [['idPrenotazione', 'idServizio'], 'integer'],
            [['idPrenotazione', 'idServizio'], 'unique', 'targetAttribute' => ['idPrenotazione', 'idServizio']],
            [['idPrenotazione'], 'exist', 'skipOnError' => true, 'targetClass' => Prenotazione::className(), 'targetAttribute' => ['idPrenotazione' => 'idPrenotazione']],
            [['idServizio'], 'exist', 'skipOnError' => true, 'targetClass' => Servizio::className(), 'targetAttribute' => ['idServizio' => 'idServizio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idPrenotazione' => Yii::t('app', 'Id Prenotazione'),
            'idServizio' => Yii::t('app', 'Id Servizio'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPrenotazione0()
    {
        return $this->hasOne(Prenotazione::className(), ['idPrenotazione' => 'idPrenotazione']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdServizio0()
    {
        return $this->hasOne(Servizio::className(), ['idServizio' => 'idServizio']);
    }
}
