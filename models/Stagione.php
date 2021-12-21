<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stagione".
 *
 * @property int $idStagione
 * @property string|null $inizio
 * @property string|null $fine
 * @property int $idAlbergo
 *
 * @property Prezzocamera[] $prezzocamere
 * @property Tipostanza[] $TipoStanze
 * @property Prezzoservizio[] $prezzoservizi
 * @property Servizio[] $Servizi
 * @property Albergo $Albergo
 */
class Stagione extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stagione';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inizio', 'fine'], 'safe'],
            [['idAlbergo'], 'required'],
            [['idAlbergo'], 'integer'],
            [['idAlbergo'], 'exist', 'skipOnError' => true, 'targetClass' => Albergo::className(), 'targetAttribute' => ['idAlbergo' => 'idAlbergo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idStagione' => Yii::t('app', 'Id Stagione'),
            'inizio' => Yii::t('app', 'Inizio'),
            'fine' => Yii::t('app', 'Fine'),
            'idAlbergo' => Yii::t('app', 'Id Albergo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrezzocamere()
    {
        return $this->hasMany(Prezzocamera::className(), ['idStagione' => 'idStagione']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoStanze()
    {
        return $this->hasMany(Tipostanza::className(), ['idTipoStanza' => 'idTipoStanza'])->viaTable('prezzocamera', ['idStagione' => 'idStagione']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrezzoservizi()
    {
        return $this->hasMany(Prezzoservizio::className(), ['idStagione' => 'idStagione']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServizi()
    {
        return $this->hasMany(Servizio::className(), ['idServizio' => 'idServizio'])->viaTable('prezzoservizio', ['idStagione' => 'idStagione']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbergo()
    {
        return $this->hasOne(Albergo::className(), ['idAlbergo' => 'idAlbergo']);
    }
}
