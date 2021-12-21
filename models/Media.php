<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property int $idMedia
 * @property string|null $urlMedia
 * @property string|null $descrizioneMedia
 * @property int $idTipoCamera
 * @property int $idAlbergo
 *
 * @property Albergo $albergo
 * @property TipoCamera $tipoCamera
 */
class Media extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'idAlbergo'], 'required'],
            [['idTipoCamera', 'idAlbergo'], 'integer'],
            [['urlMedia'], 'string', 'max' => 200],
            [['descrizioneMedia'], 'string', 'max' => 45],
            [['idAlbergo'], 'exist', 'skipOnError' => true, 'targetClass' => Albergo::className(), 'targetAttribute' => ['idAlbergo' => 'idAlbergo']],
            [['idTipoCamera'], 'exist', 'skipOnError' => true, 'targetClass' => TipoCamera::className(), 'targetAttribute' => ['idTipoCamera' => 'idTipoCamera']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idMedia' => Yii::t('app', 'Id Media'),
            'urlMedia' => Yii::t('app', 'Url Media'),
            'descrizioneMedia' => Yii::t('app', 'Descrizione Media'),
            'idTipoCamera' => Yii::t('app', 'Id Tipo Camera'),
            'idAlbergo' => Yii::t('app', 'Id Albergo'),
        ];
    }

    /**
     * Gets query for [[IdAlbergo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlbergo()
    {
        return $this->hasOne(Albergo::className(), ['idAlbergo' => 'idAlbergo']);
    }

    /**
     * Gets query for [[IdTipoCamera0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoCamera()
    {
        return $this->hasOne(TipoCamera::className(), ['idTipoCamera' => 'idTipoCamera']);
    }
}
