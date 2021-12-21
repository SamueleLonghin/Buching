<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "albergo".
 *
 * @property int $idAlbergo
 * @property string|null $nomeAlbergo
 * @property string|null $indirizzoAlbergo
 * @property string|null $noteAlbergo
 *
 * @property Camera[] $camere
 * @property Stagione[] $stagioni
 * @property Stagione $stagione
 */
class Albergo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'albergo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['noteAlbergo'], 'string'],
            [['nomeAlbergo', 'indirizzoAlbergo'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idAlbergo' => Yii::t('app', 'Id Albergo'),
            'nomeAlbergo' => Yii::t('app', 'Nome Albergo'),
            'indirizzoAlbergo' => Yii::t('app', 'Indirizzo Albergo'),
            'noteAlbergo' => Yii::t('app', 'Note Albergo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCamere()
    {
        return $this->hasMany(Camera::className(), ['idAlbergo' => 'idAlbergo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStagioni()
    {
        return $this->hasMany(Stagione::className(), ['idAlbergo' => 'idAlbergo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStagione()
    {
        //todo Controllo sulla stagione attuale
        $s = $this->hasOne(Stagione::className(), ['idAlbergo' => 'idAlbergo'])->orderBy('inizioStagione')->andFilterWhere([
            'and',
            ['<=', 'inizioStagione', 'now()'],
            ['>=', 'fineStagione', 'now()']

        ]);
//        var_dump($s->count());die();
        if ($s->count()) {
            return $s;
        } else {
            return $this->hasOne(Stagione::className(), ['idAlbergo' => 'idAlbergo'])->orderBy('inizioStagione');
        }
    }
}
