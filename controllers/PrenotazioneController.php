<?php

namespace app\controllers;

use Yii;
use app\models\Prenotazione;
use app\models\Albergo;
use app\models\Camera;
use app\models\Stagione;
use app\models\Servizio;
use app\models\ServiziModel;
use app\models\PrenotazioneHasServizio;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\TipoCamera;
use app\models\Media;

/**
 * PrenotazioneController implements the CRUD actions for Prenotazione model.
 */
class PrenotazioneController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }



    /**
     * Lists all Prenotazione models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login', 'next' => $_SERVER['REQUEST_URI']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Prenotazione::find()->where(['idCliente' => Yii::$app->user->id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Prenotazione model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Prenotazione model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Prenotazione();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idPrenotazione]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Prenotazione model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        die('error');
        $model = $this->findModel($id);
        return $this->actionPrenota(null, null, null, null, $id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idPrenotazione]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Prenotazione model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Prenotazione model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Prenotazione the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Prenotazione::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function actionPrenota($idAlbergo = null, $idTipoCamera = null, $A = null, $P = null, $id = null, $conferma = 0, $forza = 0)
    {
        /**
         * NEL CASO L'UTENTE NON SIA ANCORA REGISTRATO
         */

        if (Yii::$app->user->isGuest) {
            // var_dump(Yii::$app->controller->action->id);
            // var_dump($_SERVER['REQUEST_URI']);
            // die();
            return $this->redirect(['site/login', 'next' => $_SERVER['REQUEST_URI']]);
        }
        $a = new Albergo();
        $t = new TipoCamera();
        $model = new  Prenotazione();
        $serv = new ServiziModel();

        //Serve per il caricamento dalla pagina home dove ho fatto la ricerca per date (se mai la farò)
        $model->arrivo = is_null($A) ? $model->arrivo : $A;
        $model->partenza = is_null($P) ? $model->partenza : $P;


        /**
         * NEL CASO L'UTENTE STIA MODIFICANDO UNA PRENOTAZIONE O DEBBA ANCORA ACCETTARE
         */
        if (!is_null($id)) {
            $model = $this->findModel($id);
            /**
             * Controllo se la prenotazione è a suo nome
             */
            if (Yii::$app->user->id != $model->idCliente) {
                return $this->redirect(['index']);
            }
            //            var_dump($forza);die();
            if ($forza) {
                $model->statoPrenotazione = "cc";
                //                var_dump($model);
                $model->save();
                //                die();
            }
            /**
             * Carico i servizi che aveva già prenotato
             */
            $serv->S = array_keys(ArrayHelper::map($model->servizi, 'idServizio', 'nomeServizio'));

            /**
             * Se era già prenotata mostro la pagina di conferma, se ha già confermato la imposto come ACCETTATA e salvo
             */
            if ($model->statoPrenotazione == 'CREATA') {
                $model->costoPrenotazione = $model->getCosto();

                if ($conferma) {
                    $model->statoPrenotazione = "ACCETTATA";
                    $model->save();
                    return $this->redirect(['index']);
                }
            }
        }
        $a = Albergo::find()->where(['idAlbergo' => $idAlbergo])->one();
        $t = TipoCamera::find()->where(['idTipoCamera' => $idTipoCamera])->one();
        $model->idCliente = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post())) {
            /**
             * CONVERTO LE DATE INSERITE DALL'UTENTE
             */
            if (!is_null($model->DateVarie)) {
                $f = explode(' to ', $model->DateVarie);
                if (count($f) == 2) {
                    $model->arrivo = $f[0];
                    $model->partenza = $f[1];
                }
            }
            //            if (is_null($model->idCamera) and $model->o) {

            $stanza = Camera::getCamereNonPrenotate($model->arrivo, $model->partenza, $idAlbergo, $idTipoCamera, $model->occupanti)->one();
            //            $stanza = Camera::getStanzaLibera($model->arrivo, $model->partenza, $idAlbergo, $idTipoCamera, $model->occupanti);
            //            echo "<pre>";
            //            var_dump($stanza);
            //            var_dump($model);
            //            die();
            //                $stanza = $model->idCamera ? $model->camera : $stanza->load(['Camera' => Camera::getStanzaLibera($model->arrivo, $model->partenza, $idAlbergo, $idtipoCamera, $model->occupanti)]);
            if (!empty($stanza)) {
                $model->idCamera = $stanza['idCamera'];
            } else {
                echo "<pre> <h1>Stanza non trovata</h1>";
                var_dump($stanza);
                die();
            }
            //            }

            /**
             * SALVO LA PRENOTAZIONE
             */
            $model->statoPrenotazione = "CREATA";

            if ($model->save()) {
                /**
                 * Controllo se sono presenti servizi nella prenotazione
                 */
                if ($serv->load(Yii::$app->request->post())) {
                    /**
                     * Rimuovo i vecchi servizi per questa prenotazione nel caso questa sia un'operazione di update
                     */
                    PrenotazioneHasServizio::deleteAll(['idPrenotazione' => $model->idPrenotazione]);
                    if (is_array($serv->S)) {
                        foreach ($serv->S as $servizio) {
                            $phs = new PrenotazioneHasServizio();
                            $phs->idPrenotazione = $model->idPrenotazione;
                            $phs->idServizio = $servizio;
                            /**
                             * Risalvo i singoli servizi uno alla volta
                             */
                            if (!$phs->save()) {
                                echo "<h1>Prenotazione Has Stanza NON Salvato</h1><pre>";
                                var_dump($serv);
                                die();
                            }
                        }
                    }
                }
                /**
                 * SALVATA in stato di CREATA
                 * ora ricarico la pagina per mostrare quella di conferma (seconda parte del form)
                 */
                return $this->redirect(['prenota', 'id' => $model->idPrenotazione]);
            } else {
                echo "<pre>";
                var_dump($model);
                die("prenotazione non salvata");
            }
        }
        //        $servizi =$a->stagione->getServizi()->asArray()->asArray()->all() ;
        $a = $idAlbergo ? $a : $model->camera->albergo;
        $t = $idTipoCamera ? $t : $model->camera->tipoCamera;
        $servizi = $a->stagione->getServizi();
        $servizi->asArray();
        //        echo "<pre>";
        //        var_dump($t);
        //        die();

        //        $c = Camera::getStanzePrenotazioni($model->arrivo, $model->partenza, null, $idAlbergo, $idtipoCamera, false);
        //        $c = Camera::getStanzeNonPrenotateRaggruppate($model->arrivo, $model->partenza, $idAlbergo, $idTipoCamera);


        $imgs = Media::find()->where(['idAlbergo'=>$a->idAlbergo,'idTipoCamera' => $t->idTipoCamera])->orWhere(['idAlbergo'=>$a->idAlbergo,'idTipoCamera' => null])->all();


        return $this->render('prenota', ['model' => $model, 'serv' => $serv, 'servizi' => $servizi->all(), "a" => $a, 't' => $t, 'imgs' => $imgs]); //'stanze' => $c,
    }

    public function actionGetstanze($idAlbergo = null, $idTipoCamera = null, $A = null, $P = null, $F = null, $occ = false)
    {


        if (!is_null($F)) {
            $f = explode(' to ', $F);
            if (count($f) == 2) {
                $A = $f[0];
                $P = $f[1];
            }
        }


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

        $RisFin = (new \yii\db\Query())
            ->select([
                ' Concat( postiCamera ," posti (disponibili ",count(*)," camere)") as text ',
                //                'group_concat( cam.posticamera) as postiletto',
                'posticamera as id',
            ])
            ->from(['cam' => $Ris])
            ->groupBy('posticamera');

        //       echo "<pre>";var_dump($RisFin->all());die();
        //        echo "<pre>";var_dump($Ris->all());die();
        //        $camere->all();
        //        echo "<pre>";var_dump($RisFin->all());die();


        //        $camere = (new \yii\db\Query())->select('camera.*')->from('camera')->innerJoin("albergo", 'camera.idAlbergo = albergo.idAlbergo')->innerJoin("stagione", 'albergo.idAlbergo = stagione.idAlbergo')->innerJoin('tipoCamera', 'camera.idtipoCamera = tipoCamera.idtipoCamera')->innerJoin("prezzoservizio", 'stagione.idStagione = prezzoservizio.idStagione')->innerJoin("servizio", 'prezzoservizio.idServizio = servizio.idServizio')->innerJoin("prezzocamera", 'prezzocamera.idStagione = stagione.idStagione and prezzocamera.idtipoCamera = camera.idtipoCamera')->groupBy('camera.idCamera');
        //        if (!is_null($idAlbergo)) {
        //            $camere->andWhere(['camera.idAlbergo' => $idAlbergo]);
        //        }
        //
        //        if (!is_null($idTipoCamera)) {
        //            $camere->andWhere(['camera.idtipoCamera' => $idTipoCamera]);
        //        }
        //        $prenotazioni = (new \yii\db\Query())->select('*')->from('prenotazione');
        //        if (!(is_null($A) || is_null($P))) {
        //            $prenotazioni->andFilterWhere([
        //                'or',
        //                [
        //                    'and',
        //                    ['>=', 'arrivo', $A],
        //                    ['<=', 'arrivo', $P]
        //                ],
        //                [
        //                    'and',
        //                    ['>=', 'partenza', $A],
        //                    ['<=', 'partenza', $P]
        //                ], [
        //                    'and',
        //                    ['<=', 'arrivo', $A],
        //                    ['>=', 'partenza', $P]
        //                ]
        //            ]);
        //        }
        //
        //        $prenCamere = (new \yii\db\Query())->select('c.idCamera')->distinct()->from(['c' => $camere])->innerJoin(['p' => $prenotazioni], 'p.idCamera = c.idCamera');
        //        $camereGiuste = $camere->select('idCamera')->createCommand()->getRawSql();
        //        $camerePrenotate = $prenCamere->createCommand()->getRawSql();
        //
        //        $data = Yii::$app->db->createCommand("
        //select postiCamera as id,
        // Concat( count(*),\" camere da \",postiCamera ,\" posti\") as text
        // from camera
        // where
        // idCamera in ( " . $camereGiuste . ")
        // and
        // idCamera not in ( " . $camerePrenotate . ")
        // group by postiCamera
        // ")->queryAll();
        //        echo "<pre>";
        //        var_dump($prenCamere->all());
        //        var_dump($camere->all());
        //        var_dump($data);
        //        die();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $out['results'] = array_values($RisFin->all());
        return $out;
    }

    public
    function actionPaga($id)
    {

        $model = $this->findModel($id);
        //        $prezzo = $model->camera->tipoCamera->stagioni;
        //        $prezzo = $model->camera->prezzicamera;
        //        echo "<pre>";var_dump($model->costoCamera);
        $model->costoPrenotazione = $model->costo;
        $model->save();
        return $this->actionView($id);
    }

    public function actionGetprezzo($id)
    {
        $model = $this->findModel($id);
        echo  $model->getCosto();
    }

    public function actionFattura($id)
    {
        $model = $this->findModel($id);
        return $this->renderPartial('fattura', ['model' => $model]);
    }
}
