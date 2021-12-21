<?php

namespace app\models;

use Yii;
use app\models\Albergo;

/**
 * This is the model class for table "camera".
 *
 * @property int $idCamera
 * @property string $numeroCamera
 * @property int $pianoCamera
 * @property int $posti
 * @property string $note
 * @property int $idAlbergo
 * @property int $idtipoCamera
 *
 * @property Albergo $albergo
 * @property tipoCamera $tipoCamera
 * @property Prenotazione[] $prenotazioni
 * @property Prezzocamera[] $prezzocamera
 * @property Stagione[] $stagioni
 */
class Camera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'camera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numeroCamera', 'pianoCamera', 'posti', 'note', 'idAlbergo', 'idtipoCamera'], 'required'],
            [['pianoCamera', 'posti', 'idAlbergo', 'idTipoCamera'], 'integer'],
            [['note'], 'string'],
            [['numeroCamera'], 'string', 'max' => 45],
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
            'idCamera' => Yii::t('app', 'Id Camera'),
            'numeroCamera' => Yii::t('app', 'NumeroCamera'),
            'pianoCamera' => Yii::t('app', 'PianoCamera'),
            'posti' => Yii::t('app', 'Posti'),
            'note' => Yii::t('app', 'Note'),
            'idAlbergo' => Yii::t('app', 'Id Albergo'),
            'idTipoCamera' => Yii::t('app', 'Id Tipo Camera'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbergo()
    {
        return $this->hasOne(Albergo::className(), ['idAlbergo' => 'idAlbergo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function gettipoCamera()
    {
        return $this->hasOne(TipoCamera::className(), ['idTipoCamera' => 'idTipoCamera']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrenotazioni()
    {
        return $this->hasMany(Prenotazione::className(), ['idCamera' => 'idCamera']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrezzicamera()
    {
        return $this->hasMany(Prezzocamera::className(), ['idTipoCamera' => 'idTipoCamera'])->viaTable('tipoCamera', ['idTipoCamera' => 'idTipoCamera'])->innerJoin('stagione', 'prezzocamera.idStagione = stagione.idStagione')->where(['stagione.idAlbergo' => $this->idAlbergo]); //
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrezzocamera()
    {
        return $this->hasMany(Prezzocamera::className(), ['idTipoCamera' => 'idTipoCamera'])->viaTable('tipoCamera', ['idTipoCamera' => 'idTipoCamera'])->innerJoin('stagione', 'prezzocamera.idStagione = stagione.idStagione')->where(['stagione.idAlbergo' => $this->idAlbergo]); //
    }


    public static function getStanzeTipoAlbergo($q = null, $idAlbergo = null, $idtipoCamera = null, $ragg = true, $raw = false, $rand = null, $occupanti = false)
    {
        $camere = (new \yii\db\Query())->select('camera.idCamera,nomeAlbergo,indirizzoAlbergo,camera.idtipoCamera,nometipoCamera,camera.idAlbergo,postiCamera,prezzocamera.costoTipoCamera,group_concat(Distinct servizio.nomeServizio ) as servizi')->from('camera')->innerJoin("albergo", 'camera.idAlbergo = albergo.idAlbergo')->innerJoin("stagione", 'albergo.idAlbergo = stagione.idAlbergo')->innerJoin('tipoCamera', 'camera.idtipoCamera = tipoCamera.idtipoCamera')->innerJoin("prezzoservizio", 'stagione.idStagione = prezzoservizio.idStagione')->innerJoin("servizio", 'prezzoservizio.idServizio = servizio.idServizio')->innerJoin("prezzocamera", 'prezzocamera.idStagione = stagione.idStagione and prezzocamera.idtipoCamera = camera.idtipoCamera')->groupBy('camera.idCamera');
        if (!is_null($rand)) {
            $camere->limit(10);
        }
        if (!is_null($q)) {
            foreach (explode(' ', trim($q)) as $r) {
                $like = "like";
                $camere->orWhere([$like, 'nomeAlbergo', $r])->orWhere([$like, 'indirizzoAlbergo', $r])->orWhere([$like, 'servizio.nomeServizio', $r])->orWhere([$like, 'tipoCamera.nometipoCamera', $r]);
            }
        }
        if (!is_null($idAlbergo)) {
            $camere->andWhere(['camera.idAlbergo' => $idAlbergo]);
        }
        if (!is_null($occupanti) && $occupanti != "") {
            $camere->andWhere('camera.postiCamera > ' . $occupanti);
        }
        if (!is_null($idtipoCamera)) {
            $camere->andWhere(['camera.idtipoCamera' => $idtipoCamera]);
        }
        if ($ragg) {
            $camere = (new \yii\db\Query())->select('*,count(*) as quante,group_concat(Distinct postiCamera ) as postiletto')->from(['c' => $camere])->groupBy('idtipoCamera, c.idAlbergo')->orderBy("count(*) desc, costoTipoCamera");
        }
//        var_dump($tipoCamereAlbergo->createCommand()->rawSql);die();
        return $raw ? $camere : $camere->all();
    }

    public static function getStanzePrenotazioni($Arrivo, $Partenza, $q = null, $idAlbergo = null, $idtipoCamera = null, $occ = false, $ragg = false)
    {
        $camere = self::getStanzeTipoAlbergo($q, $idAlbergo, $idtipoCamera, false, true);
        $prenotazioni = (new \yii\db\Query())->select('*')->from('prenotazione');
        if (!(is_null($Arrivo) || is_null($Partenza))) {
//            die($Arrivo);
            $prenotazioni->andFilterWhere([
//                'not', [
                'or',
                [
                    'and',
                    ['>=', 'arrivo', $Arrivo],
                    ['<=', 'arrivo', $Partenza]
                ],
                [
                    'and',
                    ['>=', 'partenza', $Arrivo],
                    ['<=', 'partenza', $Partenza]
                ], [
                    'and',
                    ['<=', 'arrivo', $Arrivo],
                    ['>=', 'partenza', $Partenza]
                ]
//                ]
            ]);
        }
//        else {
//            $prenotazioni->where('false');
//        }
        $prenCamere = (new \yii\db\Query())->select('c.*')->distinct()->from(['c' => $camere])->innerJoin(['p' => $prenotazioni], 'p.idCamera = c.idCamera');
        $sql = "(" . $camere->createCommand()->rawSql . " except " . $prenCamere->createCommand()->rawSql . ")";
        $ragg = "";
        if (!$occ) {
            $ragg = (new \yii\db\Query())->select(['postiCamera as id', 'Concat( count(*)," camere da ",postiCamera ," posti") as text'])->from(['cam' => $sql])->groupBy('cam.postiCamera');
//            $ragg = (new \yii\db\Query())->select(['posti as id', 'Concat( count(*)," camere da ",posti ," posti") as text'])->from(['cam' => $sql])->groupBy('cam.posti');
            return $ragg->all();
        } else {
            $ragg = (new \yii\db\Query())->select('*')->from(['cam' => $sql])->where(['postiCamera' => $occ]);
            return $ragg->one();
        }
        //        $ragg = (new \yii\db\Query())->select('*, count(*) as quante')->from(['cam'=>$sql])->groupBy('cam.posti');
    }

    public static function getStanzeNonPrenotateRaggruppate($Arrivo, $Partenza, $idAlbergo = null, $idtipoCamera = null,$occupanti = null)
    {
        die('DISMESSA    spero');
        $camere = (new \yii\db\Query())->select('camera.idCamera,nomeAlbergo,indirizzoAlbergo,camera.idtipoCamera,nometipoCamera,camera.idAlbergo,postiCamera,prezzocamera.costoTipoCamera,group_concat(Distinct servizio.nomeServizio ) as servizi')->from('camera')->innerJoin("albergo", 'camera.idAlbergo = albergo.idAlbergo')->innerJoin("stagione", 'albergo.idAlbergo = stagione.idAlbergo')->innerJoin('tipoCamera', 'camera.idtipoCamera = tipoCamera.idtipoCamera')->innerJoin("prezzoservizio", 'stagione.idStagione = prezzoservizio.idStagione')->innerJoin("servizio", 'prezzoservizio.idServizio = servizio.idServizio')->innerJoin("prezzocamera", 'prezzocamera.idStagione = stagione.idStagione and prezzocamera.idtipoCamera = camera.idtipoCamera')->groupBy('camera.idCamera');

        if (!is_null($idAlbergo)) {
            $camere->andWhere(['camera.idAlbergo' => $idAlbergo]);
        }
        if (!is_null($occupanti) && $occupanti != "") {
            $camere->andWhere('camera.postiCamera > ' . $occupanti);
        }
        if (!is_null($idtipoCamera)) {
            $camere->andWhere(['camera.idtipoCamera' => $idtipoCamera]);
        }

        $camereSql = $camere->createCommand()->rawSql;
        $prenotazioni = (new \yii\db\Query())->select('idCamera')->from('prenotazione');
//        $prenotazioni = (new \yii\db\Query())->select('*')->from('prenotazione');
        if (!(is_null($Arrivo) || is_null($Partenza))) {
//            die($Arrivo);
            $prenotazioni->andFilterWhere([
//                'not', [
                'or',
                [
                    'and',
                    ['>=', 'arrivo', $Arrivo],
                    ['<=', 'arrivo', $Partenza]
                ],
                [
                    'and',
                    ['>=', 'partenza', $Arrivo],
                    ['<=', 'partenza', $Partenza]
                ], [
                    'and',
                    ['<=', 'arrivo', $Arrivo],
                    ['>=', 'partenza', $Partenza]
                ]
//                ]
            ]);
        }
//        else {
//            $prenotazioni->where('false');
//        }
        $prenCamere = (new \yii\db\Query())->select('c.idCamera')->distinct()->from(['c' => $camere])->innerJoin(['p' => $prenotazioni], 'p.idCamera = c.idCamera');
//        $sql = "(" . $camere->createCommand()->rawSql . " except " . $prenCamere->createCommand()->rawSql . ")";
        $ragg = "";
       $camere->andWhere('idCamera not in ('.$prenCamere->createCommand()->rawSql.')');
       return $camere->all();
        //        $ragg = (new \yii\db\Query())->select('*, count(*) as quante')->from(['cam'=>$sql])->groupBy('cam.posti');
    }

    public static function getCamereNonPrenotate($A, $P, $idAlbergo = null, $idTipoCamera = null, $occupanti = null)
    {
        $camere = (new \yii\db\Query())
            ->select('
            idCamera,
            nomeAlbergo,
            indirizzoAlbergo,
            camera.idtipoCamera,
            nometipoCamera,
            camera.idAlbergo,
            postiCamera,
            costoTipoCamera')
            ->from('camera')
            ->innerJoin("albergo", 'camera.idAlbergo = albergo.idAlbergo')
            ->innerJoin("stagione", 'albergo.idAlbergo = stagione.idAlbergo')
            ->innerJoin('tipoCamera', 'camera.idtipoCamera = tipoCamera.idtipoCamera')
            ->innerJoin("prezzocamera", 'prezzocamera.idStagione = stagione.idStagione and prezzocamera.idtipoCamera = camera.idtipoCamera')
            ->groupBy('camera.idCamera');

        if (!is_null($idAlbergo)) {
            $camere->andWhere(['camera.idAlbergo' => $idAlbergo]);
        }

        if (!is_null($idTipoCamera)) {
            $camere->andWhere(['camera.idtipoCamera' => $idTipoCamera]);
        }
        if (!is_null($occupanti) && $occupanti != "") {
            $camere->andWhere('camera.postiCamera >= ' . $occupanti)->orderBy('postiCamera');
        }
        $prenotazioni = (new \yii\db\Query())->select('idCamera')->from('prenotazione');
        if (!(is_null($A) || is_null($P))) {
            $prenotazioni->andFilterWhere([
                'or',
                [
                    'and',
                    ['>=', 'arrivo', $A],
                    ['<=', 'arrivo', $P]
                ],
                [
                    'and',
                    ['>=', 'partenza', $A],
                    ['<=', 'partenza', $P]
                ], [
                    'and',
                    ['<=', 'arrivo', $A],
                    ['>=', 'partenza', $P]
                ]
            ]);
        }
        $Ris = (new \yii\db\Query())
            ->select([
                'idCamera',
                'posticamera',
                'idTipoCamera'
            ])
            ->where(' idCamera not in (' . $prenotazioni->createCommand()->rawSql . ')')
            ->from(['c' => $camere])
            ->innerJoin("stagione", 'c.idAlbergo = stagione.idAlbergo')
            ->groupBy('idCamera');

        return $Ris;
        $RisFin = (new \yii\db\Query())
            ->select([
                ' Concat( count(*)," camere da ",postiCamera ," posti") as text ',
//                'group_concat( cam.posticamera) as postiletto',
                'posticamera as id',
            ])
            ->from(['cam' => $Ris])
            ->groupBy('posticamera');
    }


    public static  function  getStanzaLibera($Arrivo, $Partenza, $idAlbergo = null, $idtipoCamera = null,$posti){
        $camere = (new \yii\db\Query())
            ->select('camera.*')
            ->from('camera')
            ->innerJoin("albergo", 'camera.idAlbergo = albergo.idAlbergo')
            ->innerJoin("stagione", 'albergo.idAlbergo = stagione.idAlbergo')
            ->innerJoin('tipoCamera', 'camera.idtipoCamera = tipoCamera.idtipoCamera')
//            ->innerJoin("prezzoservizio", 'stagione.idStagione = prezzoservizio.idStagione')
//            ->innerJoin("servizio", 'prezzoservizio.idServizio = servizio.idServizio')
            ->innerJoin("prezzocamera", 'prezzocamera.idStagione = stagione.idStagione and prezzocamera.idtipoCamera = camera.idtipoCamera')
            ->groupBy('camera.idCamera');
        if (!is_null($idAlbergo)) {
            $camere->andWhere(['camera.idAlbergo' => $idAlbergo]);
        }
        if (!is_null($posti) && $posti != "") {
            $camere->andWhere('camera.postiCamera >= ' . $posti);
        }
        if (!is_null($idtipoCamera)) {
            $camere->andWhere(['camera.idtipoCamera' => $idtipoCamera]);
        }
        $prenotazioni = (new \yii\db\Query())->select('*')->from('prenotazione');
        if (!(is_null($Arrivo) || is_null($Partenza))) {
            $prenotazioni->andFilterWhere([
                'or',
                [
                    'and',
                    ['>=', 'arrivo', $Arrivo],
                    ['<=', 'arrivo', $Partenza]
                ],
                [
                    'and',
                    ['>=', 'partenza', $Arrivo],
                    ['<=', 'partenza', $Partenza]
                ], [
                    'and',
                    ['<=', 'arrivo', $Arrivo],
                    ['>=', 'partenza', $Partenza]
                ]
            ]);
        }

        $prenCamere = (new \yii\db\Query())->select('c.idCamera')->distinct()->from(['c' => $camere])->innerJoin(['p' => $prenotazioni], 'p.idCamera = c.idCamera');

        //       echo "<pre>";var_dump($this->getPrezzicamera()->andWhere(1)->all());die();
//       echo "<pre>";var_dump($prenCamere->all());die();
        return $camere->where('idCamera not in('.$prenCamere->createCommand()->rawSql.')')->one();
        return Yii::$app->db->createCommand("select * from camera where idCamera in ( ".$camere->select('idCamera')->createCommand()->getRawSql().") and idCamera not in ( ".$prenCamere->select('camera.idCamera')->createCommand()->getRawSql().")")->queryOne();

    }

    public function Stagioni($arr, $part)
    {
//       echo "<pre>";var_dump($this->getPrezzicamera()->andWhere(1)->all());die();
//       echo "<pre>";var_dump($this->getPrezzicamera()->createCommand());die();
    }


    public static function getStanzeTipoAlbergoDate($a, $p, $q = null, $idAlbergo = null, $idtipoCamera = null, $ragg = true, $raw = false, $rand = null, $occupanti = false)
    {
        $alb = Stagione::find()->where('stagione.idAlbergo = 4')->innerJoin('albergo', 'albergo.idAlbergo = stagione.idAlbergo')->all();
        $raw = self::getStanzeTipoAlbergo($q, $idAlbergo, $idtipoCamera, false, true, $rand, $occupanti)->andWhere("stagione.inizioStagione = 1")->select("*");
        return self::getStanzeTipoAlbergo($q, $idAlbergo, $idtipoCamera, $ragg, $raw, $rand, $occupanti);
        echo "<pre>";
        var_dump($alb);
        die();
        echo "<pre>";
        var_dump($raw->all());
        die();
    }




}
