<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "utente_has_albergo".
 *
 * @property int $idUtente
 * @property int $idAlbergo
 *
 * @property Albergo $idAlbergo0
 * @property Cliente $idUtente0
 */
class UtenteHasAlbergo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utente_has_albergo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idUtente', 'idAlbergo'], 'required'],
            [['idUtente', 'idAlbergo'], 'integer'],
            [['idUtente', 'idAlbergo'], 'unique', 'targetAttribute' => ['idUtente', 'idAlbergo']],
            [['idAlbergo'], 'exist', 'skipOnError' => true, 'targetClass' => Albergo::className(), 'targetAttribute' => ['idAlbergo' => 'idAlbergo']],
            [['idUtente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['idUtente' => 'idCliente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idUtente' => 'Id Utente',
            'idAlbergo' => 'Id Albergo',
        ];
    }

    /**
     * Gets query for [[IdAlbergo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdAlbergo0()
    {
        return $this->hasOne(Albergo::className(), ['idAlbergo' => 'idAlbergo']);
    }

    /**
     * Gets query for [[IdUtente0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdUtente0()
    {
        return $this->hasOne(Cliente::className(), ['idCliente' => 'idUtente']);
    }
}
