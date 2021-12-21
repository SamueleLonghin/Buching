<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipoCamera".
 *
 * @property int $idTipoCamera
 * @property string|null $nomeTipoCamera
 * @property string|null $descrizione
 *
 * @property Camera[] $cameras
 * @property Media[] $media
 * @property Prezzocamera[] $prezzocameras
 * @property Stagione[] $idStagiones
 */
class TipoCamera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipoCamera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nomeTipoCamera', 'descrizione'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idTipoCamera' => 'Id Tipo Camera',
            'nomeTipoCamera' => 'Nome Tipo Camera',
            'descrizione' => 'Descrizione',
        ];
    }

    /**
     * Gets query for [[Cameras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCameras()
    {
        return $this->hasMany(Camera::className(), ['idTipoCamera' => 'idTipoCamera']);
    }

    /**
     * Gets query for [[Media]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['idTipoCamera' => 'idTipoCamera']);
    }

    /**
     * Gets query for [[Prezzocameras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrezzocameras()
    {
        return $this->hasMany(Prezzocamera::className(), ['idTipoCamera' => 'idTipoCamera']);
    }

    /**
     * Gets query for [[IdStagiones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdStagiones()
    {
        return $this->hasMany(Stagione::className(), ['idStagione' => 'idStagione'])->viaTable('prezzocamera', ['idTipoCamera' => 'idTipoCamera']);
    }
}
