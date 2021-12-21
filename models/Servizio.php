<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "servizio".
 *
 * @property int $idServizio
 * @property string|null $nomeServizio
 * @property string|null $descrizione
 *
 * @property PrenotazioneHasServizio[] $prenotazioneHasServizios
 * @property Prenotazione[] $idPrenotaziones
 * @property Prezzoservizio[] $prezzoservizios
 * @property Stagione[] $idStagiones
 */
class Servizio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servizio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nomeServizio', 'descrizione'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idServizio' => Yii::t('app', 'Id Servizio'),
            'nomeServizio' => Yii::t('app', 'Nome'),
            'descrizione' => Yii::t('app', 'Descrizione'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrenotazioneHasServizios()
    {
        return $this->hasMany(PrenotazioneHasServizio::className(), ['idServizio' => 'idServizio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPrenotaziones()
    {
        return $this->hasMany(Prenotazione::className(), ['idPrenotazione' => 'idPrenotazione'])->viaTable('prenotazione_has_servizio', ['idServizio' => 'idServizio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrezzoservizios()
    {
        return $this->hasMany(Prezzoservizio::className(), ['idServizio' => 'idServizio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdStagiones()
    {
        return $this->hasMany(Stagione::className(), ['idStagione' => 'idStagione'])->viaTable('prezzoservizio', ['idServizio' => 'idServizio']);
    }
}
