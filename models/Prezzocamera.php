<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prezzocamera".
 *
 * @property int $idTipoStanza
 * @property int $idStagione
 * @property float|null $costo
 *
 * @property Media[] $media
 * @property Stagione $idStagione0
 * @property Tipostanza $idTipoStanza0
 */
class Prezzocamera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prezzocamera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idTipoStanza', 'idStagione'], 'required'],
            [['idTipoStanza', 'idStagione'], 'integer'],
            [['costo'], 'number'],
            [['idTipoStanza', 'idStagione'], 'unique', 'targetAttribute' => ['idTipoStanza', 'idStagione']],
            [['idStagione'], 'exist', 'skipOnError' => true, 'targetClass' => Stagione::className(), 'targetAttribute' => ['idStagione' => 'idStagione']],
            [['idTipoStanza'], 'exist', 'skipOnError' => true, 'targetClass' => Tipostanza::className(), 'targetAttribute' => ['idTipoStanza' => 'idTipoStanza']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idTipoStanza' => Yii::t('app', 'Id Tipo Stanza'),
            'idStagione' => Yii::t('app', 'Id Stagione'),
            'costo' => Yii::t('app', 'Costo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['idTipoStanza' => 'idTipoStanza']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdStagione0()
    {
        return $this->hasOne(Stagione::className(), ['idStagione' => 'idStagione']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoStanza0()
    {
        return $this->hasOne(Tipostanza::className(), ['idTipoStanza' => 'idTipoStanza']);
    }
}
