<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prenotazione".
 *
 * @property int $idPrenotazione
 * @property string $arrivo
 * @property string $partenza
 * @property int $occupanti
 * @property string|null $Note
 * @property string|null $DateVarie
 * @property int $idCliente
 * @property int $idCamera
 * @property doube $costoPrenotazione
 * @property string $statoPrenotazione
 *
 * @property Camera $camera
 * @property Cliente $cliente
 * @property PrenotazioneHasServizio[] $prenotazioneHasServizios
 * @property Servizio[] $servizi
 */
class Prenotazione extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prenotazione';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['arrivo', 'partenza', 'occupanti', 'idCliente', 'idCamera', 'DateVarie'], 'required'],
            [['arrivo', 'partenza', 'costoPrenotazione'], 'safe'],
            [['occupanti', 'idCliente', 'idCamera'], 'integer'],
            [['Note', 'DateVarie'], 'string'],
            ['costoPrenotazione', 'double'],
            [['statoPrenotazione'], 'string', 'max' => 20],
            [['idCamera'], 'exist', 'skipOnError' => true, 'targetClass' => Camera::className(), 'targetAttribute' => ['idCamera' => 'idCamera']],
            [['idCliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['idCliente' => 'idCliente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idPrenotazione' => Yii::t('app', 'Id'),
            'arrivo' => Yii::t('app', 'Arrivo'),
            'partenza' => Yii::t('app', 'Partenza'),
            'occupanti' => Yii::t('app', 'Occupanti'),
            'Note' => Yii::t('app', 'Note'),
            'costoPrenotazione' => Yii::t('app', 'Costo'),
            'idCliente' => Yii::t('app', 'Id Cliente'),
            'idCamera' => Yii::t('app', 'Id Camera'),
            'statoPrenotazione' => Yii::t('app', 'Stato Prenotazione'),
            'DateVarie' => Yii::t('app', 'Arrivo e Partenza'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCamera()
    {
        return $this->hasOne(Camera::className(), ['idCamera' => 'idCamera']);
    }

    /**
     * @return string
     */
    public function getNomeCamera()
    {
        $c = $this->hasOne(Camera::className(), ['idCamera' => 'idCamera'])->one();
        return "Camera " . $c->numeroCamera . " piano " . $c->pianoCamera . " (" . $c->idCamera . ") ";
    }

    /**
     * @return string
     */
    public function getNomePrenotazione()
    {
        $c = $this->hasOne(Camera::className(), ['idCamera' => 'idCamera'])->one();
        return $c->tipoCamera->nomeTipoCamera . " su " . $c->albergo->nomeAlbergo . ", " . is_null($this->DateVarie) ? "" : $this->DateVarie;
    }

    /**
     * @return string
     */
    public function getNomeTipoCamera()
    {
//        var_dump($this->camera->tipoCamera);die();
        return $this->camera->tipoCamera->nomeTipoCamera;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['idCliente' => 'idCliente']);
    }

    /**
     * @return string
     */
    public function getNomeCliente()
    {
        return $this->hasOne(Cliente::className(), ['idCliente' => 'idCliente'])->one()->username;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrenotazioneHasServizios()
    {
        return $this->hasMany(PrenotazioneHasServizio::className(), ['idPrenotazione' => 'idPrenotazione']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServizi()
    {
        return $this->hasMany(Servizio::className(), ['idServizio' => 'idServizio'])->viaTable('prenotazione_has_servizio', ['idPrenotazione' => 'idPrenotazione']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStagioniC()
    {
        if (!is_null($this->camera)) {
            $stagioni = (new \yii\db\Query())->select([
                'costoTipoCamera',
                'stagione.idStagione',
                'inizioStagione',
                'fineStagione',
//                ' DATEDIFF(fineStagione,"' . $this->arrivo . '") as fine_arrivo',
//                ' DATEDIFF("' . $this->partenza . '",inizioStagione) as aprtenza_inizio',
//                ' DATEDIFF(inizioStagione,"' . $this->arrivo . '") as inizzio_arrivo',
//                ' DATEDIFF(fineStagione,"' . $this->partenza . '") as fine_partenza',
            ])->from('stagione')->innerJoin('prezzocamera', 'prezzocamera.idStagione = stagione.idStagione')->where(['idAlbergo' => $this->camera->idAlbergo])->andWhere(['idTipoCamera' => $this->camera->idTipoCamera]);
            $stagioni->andFilterWhere([
                'or',
                [
                    'and',
                    ['>=', 'inizioStagione', $this->arrivo],
                    ['<=', 'inizioStagione', $this->partenza]
                ],
                [
                    'and',
                    ['>=', 'fineStagione', $this->arrivo],
                    ['<=', 'fineStagione', $this->partenza]
                ], [
                    'and',
                    ['<=', 'inizioStagione', $this->arrivo],
                    ['>=', 'fineStagione', $this->partenza]
                ]
            ]);

            return $stagioni->all();

        }
//        return $this->hasMany(Stagione::className(), ['idAlbergo' => 'idAlbergo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStagioniS($idServizio)
    {
        if (!is_null($this->camera)) {
            $stagioni = (new \yii\db\Query())->select([
                'costoServizio',
                'stagione.idStagione',
                'inizioStagione', 'fineStagione',
//                ' DATEDIFF(fineStagione,"' . $this->arrivo . '") as fine_arrivo',
//                ' DATEDIFF("' . $this->partenza . '",inizioStagione) as aprtenza_inizio',
//                ' DATEDIFF(inizioStagione,"' . $this->arrivo . '") as inizzio_arrivo',
//                ' DATEDIFF(fineStagione,"' . $this->partenza . '") as fine_partenza',
            ])->from('stagione')->innerJoin('prezzoservizio', 'prezzoservizio.idStagione = stagione.idStagione')->where(['idAlbergo' => $this->camera->idAlbergo])->where(['idServizio' => $idServizio]);
            $stagioni->andFilterWhere([
                'or',
                [
                    'and',
                    ['>=', 'inizioStagione', $this->arrivo],
                    ['<=', 'inizioStagione', $this->partenza]
                ],
                [
                    'and',
                    ['>=', 'fineStagione', $this->arrivo],
                    ['<=', 'fineStagione', $this->partenza]
                ], [
                    'and',
                    ['<=', 'inizioStagione', $this->arrivo],
                    ['>=', 'fineStagione', $this->partenza]
                ]
            ]);

            return $stagioni->all();

        }
//        return $this->hasMany(Stagione::className(), ['idAlbergo' => 'idAlbergo']);
    }

    /**
     * @return string
     */
    public function getNomeServizi()
    {
        $s = $this->hasMany(Servizio::className(), ['idServizio' => 'idServizio'])->viaTable('prenotazione_has_servizio', ['idPrenotazione' => 'idPrenotazione'])->all();
        $serviziNome = "";
        foreach ($s as $item) {
//       echo"<pre>"; var_dump($s);die();
            $serviziNome .= $item['nomeServizio'] . " ";
        }
        return $serviziNome;
    }


    /**
     * @return double
     */
    public function getCosto()
    {

        $c = $this->getCostoCamera();
        $costo = 0;
        $aa = $this->getCostoServizi();
        return $c + $aa;
    }

    /**
     * @return double
     */
    public function getCostoCamera()
    {
        $risultati = $this->getStagioniC();
        $costo = 0;
        $cosi = [];
        $a = new \DateTime($this->arrivo);
        $p = new \DateTime($this->partenza);
        foreach ($risultati as $Key => $val) {
            $inizio = max($a, new \DateTime($val['inizioStagione']));
            $fine = min($p, new \DateTime($val['fineStagione']));
            $fma = date_diff($fine->modify('+0 day'), $a); //ho messo 0 ma prima era 1
            $ima = date_diff($inizio, $a);
            $tot = $fma->days - $ima->days;
            $cosi[$val['idStagione']] = $tot;
            $costo += $tot * $val['costoTipoCamera'];
        }

        return $costo;
    }

    /**
     * @return double
     */
    public function getGiorniStagioneCamera()
    {
        $risultati = $this->getStagioniC();
        $cosi = [];
        $a = new \DateTime($this->arrivo);
        $p = new \DateTime($this->partenza);
        foreach ($risultati as $Key => $val) {
            $inizio = max($a, new \DateTime($val['inizioStagione']));
            $fine = min($p, new \DateTime($val['fineStagione']));
            $fma = date_diff($fine->modify('+0 day'), $a);//ho messo 0 ma prima era 1
            $ima = date_diff($inizio, $a);
            $tot = $fma->days - $ima->days;
            $cosi[$val['costoTipoCamera']] = isset($cosi[$val['costoTipoCamera']]) ? $cosi[$val['costoTipoCamera']] + $tot : $tot;
        }
        return $cosi;
    }

    /**
     * @return double
     */
    public function getCostoServizi()
    {
        $costo = 0;
        foreach ($this->servizi as $servizio) {
            $risultati = $this->getStagioniS($servizio->idServizio);
            $cosi = [];
            $a = new \DateTime($this->arrivo);
            $p = new \DateTime($this->partenza);
            foreach ($risultati as $Key => $val) {
                $inizio = max($a, new \DateTime($val['inizioStagione']));
                $fine = min($p, new \DateTime($val['fineStagione']));
                $fma = date_diff($fine->modify('+0 day'), $a);//ho messo 0 ma prima era 1
                $ima = date_diff($inizio, $a);
                $tot = $fma->days - $ima->days;
                $cosi[$val['idStagione']] = $tot;
                $costo += $tot * $val['costoServizio'];
            }
        }
        return $costo;
    }

    /**
     * @return double
     */
    public function getGiorniStagioneServizi()
    {
        $costo = 0;
        $cosi = [];
        foreach ($this->servizi as $servizio) {
            $risultati = $this->getStagioniS($servizio->idServizio);
            $a = new \DateTime($this->arrivo);
            $p = new \DateTime($this->partenza);
            foreach ($risultati as $Key => $val) {
                $inizio = max($a, new \DateTime($val['inizioStagione']));
                $fine = min($p, new \DateTime($val['fineStagione']));
                $fma = date_diff($fine->modify('+0 day'), $a);//ho messo 0 ma prima era 1
                $ima = date_diff($inizio, $a);
                $tot = $fma->days - $ima->days;
                $cosi[$val['costoServizio']] = isset($cosi[$val['costoServizio']]) ? $cosi[$val['costoServizio']] + $tot : $tot;
            }
        }
        return $cosi;
    }

    public function getCostoServiziO()
    {
        $prezzi = [];
        $p = date_create($this->partenza);
        $a = date_create($this->arrivo);
        $inizio = date_format($a, 'z');
        $fine = date_format($p, 'z');
        foreach ($this->servizi as $servizio) {
//            $stagioni = (new \yii\db\Query())->select('*')->from(['s' => (new \yii\db\Query())->select('*')->from('stagione')->where(['idAlbergo' => $this->camera->idAlbergo])])->all();
            $stagioni = (new \yii\db\Query())->select('*')->from(['p' => (new \yii\db\Query())->select('*')->from('prezzoservizio')->where(['idServizio' => $servizio->idServizio])])->innerJoin(['s' => (new \yii\db\Query())->select('*')->from('stagione')->where(['idAlbergo' => $this->camera->idAlbergo])], "s.idStagione = p.idStagione")->all();
//var_dump($stagioni);die("d");
            $s = [$stagioni[count($stagioni) - 1]];
            array_push($s, ...$stagioni);
            $stagioni = $s;
//            var_dump($stagioni);
//            die("ds");
            $giorni = 0;
            $i = $inizio;
            $scounter = 0;

            do {
                if ($scounter == count($stagioni) - 1) {
                    break;
//                    var_dump($prezzi);
//                    die("ciclo");
                }
                $finestagione = $stagioni[$scounter + 1]['inizioStagione'];
                if ($finestagione == $i || $i - 1 >= $fine) {
                    $prezzi[] = [$giorni, $servizio->nomeServizio, $stagioni[$scounter]['costoServizio']];
                    $scounter++;
//                    echo "<br> Servizio Aggiunto, Giorni: " . $giorni . "   Stagione: " . $stagioni[$scounter]['nomeStagione'] . '  Costo: ' . $stagioni[$scounter]['costoServizio'];
                }
//                echo "<br>giorni: ";
//                echo($giorni);
//                echo "<br>inizio: ";
//                echo($inizio);
//                echo "<br>fine: ";
//                echo($fine);
//                echo "<br>i: ";
//                echo($i);
//                echo "<br>finestagione: ";
//                echo($finestagione);
//                echo "<br> ";
                $giorni++;
                $i++;
                //Pericolo Out strani todo da vedere
            } while ($fine > $i - 2);
        }
        return $prezzi;
    }
}
