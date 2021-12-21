<?php

namespace app\models;

use Yii;
use yii\base\Security;

/**
 * This is the model class for table "cliente".
 *
 * @property int $idCliente
 * @property string $nome
 * @property string $telefono
 * @property string $email
 * @property string $password
 * @property string|null $authKey
 * @property string|null $accessToken
 *
 * @property Prenotazione[] $prenotaziones
 */
class Cliente extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'telefono', 'email', 'password'], 'required'],
            [['nome', 'telefono', 'email', 'password', 'authKey', 'accessToken'], 'string', 'max' => 45],
            [['email'], 'unique'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idCliente' => Yii::t('app', 'Id Cliente'),
            'nome' => Yii::t('app', 'Nome'),
            'telefono' => Yii::t('app', 'Telefono'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'authKey' => Yii::t('app', 'Auth Key'),
            'accessToken' => Yii::t('app', 'Access Token'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrenotaziones()
    {
        return $this->hasMany(Prenotazione::className(), ['idCliente' => 'idCliente']);
    }

    public function getUsername()
    {
        return $this->email;
    }

    /**
     *
     * Identity Login
     *
     *
     * @param $id
     * @return mixed
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
//        var_dump($this);die();
        return $this->idCliente;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Extra Login
     *
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username]);
    }

    public function validatePassword($passs)
    {
//        var_dump(Yii::$app->getSecurity());
        return $this->password == $passs;
        var_dump(Yii::$app->getSecurity()->decryptByPassword($this->password, "ss"));die();

        return  Yii::$app->getSecurity()->validatePassword($passs, $this->password);
    }

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
        var_dump($this->password);
//        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->password =Yii::$app->getSecurity()->encryptByPassword($this->password, "ss");
        var_dump($this->password);die();
        return parent::beforeSave($insert);
    }
}
