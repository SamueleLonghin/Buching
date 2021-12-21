<?php

namespace app\models;

    
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
    
class User extends  \yii\db\ActiveRecord implements \yii\web\IdentityInterface
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
           ];
       }
    
    public function getUsername(){
        return $this->email;
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
        public function validatePassword($passs){
            return $passs == $this->password;
        }
}
