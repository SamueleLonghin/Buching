<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RicercaModel;
use app\models\Albergo;
use app\models\Camera;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
//        Camera::find()->one()->stagioni(1, 2);

        $model = new RicercaModel();
        if ($model->load(Yii::$app->request->post())) {
//           echo "<pre>";var_dump($model);die();
            if (!is_null($model->date)) {
                $f = explode('_', $model->date);
                if (count($f) == 2) {
                    $model->arrivo = $f[0];
                    $model->partenza = $f[1];
                }
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
            ->innerJoin("prezzoservizio", 'stagione.idStagione = prezzoservizio.idStagione')
            ->innerJoin('tipoCamera', 'camera.idtipoCamera = tipoCamera.idtipoCamera')
            ->innerJoin("servizio", 'prezzoservizio.idServizio = servizio.idServizio')
            ->innerJoin("prezzocamera", 'prezzocamera.idStagione = stagione.idStagione and prezzocamera.idtipoCamera = camera.idtipoCamera')
            ->groupBy('camera.idCamera');
        if (!(is_null($model->q) || trim($model->q) == "")) {
            $camere->limit(10);
        }
        if (!(is_null($model->q)) && trim($model->q) != "") {
            foreach (explode(' ', trim($model->q)) as $r) {
                $like = "like";
                $camere->orWhere([$like, 'nomeAlbergo', $r])->orWhere([$like, 'indirizzoAlbergo', $r])->orWhere([$like, 'servizio.nomeServizio', $r])->orWhere([$like, 'tipoCamera.nometipoCamera', $r]);
            }
        }

        if (!is_null($model->occupanti) && $model->occupanti != 0) {
            $camere->andWhere('camera.postiCamera >= ' . $model->occupanti);
        }

        $Ris = (new \yii\db\Query())
            ->select([
                'c.idTipoCamera',
                'c.nomeTipoCamera',
                'c.costoTipoCamera',
                'c.nomeAlbergo',
                'c.indirizzoAlbergo',
                'c.idAlbergo',
                'postiCamera',
                'concat( coalesce( group_concat(Distinct AM.urlMedia),""), \',\',coalesce( group_concat(Distinct TM.urlMedia),"")) as urli',
                'group_concat(Distinct servizio.nomeServizio ) as servizi'
            ])
            ->from(['c' => $camere])
            ->innerJoin("stagione", 'c.idAlbergo = stagione.idAlbergo')
            ->innerJoin("prezzoservizio", 'stagione.idStagione = prezzoservizio.idStagione')
            ->innerJoin("servizio", 'prezzoservizio.idServizio = servizio.idServizio')
            ->leftJoin("media as TM", 'c.idTipocamera = TM.idTipoCamera and c.idAlbergo = TM.idAlbergo')
            ->leftJoin("media as AM", 'AM.idTipoCamera is null and c.idAlbergo = AM.idAlbergo')
            ->groupBy('idCamera');

        $RisFin = (new \yii\db\Query())
            ->select([
                '*',
                'group_concat( cam.posticamera) as postiletto',
                'count(*) as quante',
            ])
            ->from(['cam' => $Ris])
            ->groupBy('cam.idtipoCamera, cam.idAlbergo')
            ->orderBy("count(*) desc, cam.costoTipoCamera");

//        $camere->all();
//        echo "<pre>";var_dump($RisFin->all());
//        die();
//        return $this->render('index', ['model' => $model, 'alb' => Camera::getStanzeTipoAlbergo($model->q, null, null, true, false, true)]);
        return $this->render('index', ['model' => $model, 'alb' => $RisFin->all()]);


        //        return $this->render('index', ['model' => $model, 'alb' => Camera::getStanzeTipoAlbergoDate(null,null,$model->q, null, null, true, false, true)]);
    }


    public function actionSearch()
    {
        $model = new RicercaModel();
        return $this->render('index', ['model' => $model]);
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public
    function actionLogin($next = false)
    {
        if (!Yii::$app->user->isGuest) {
//            die("out");
            return $this->goHome();
        }
//        echo "<pre>";
//        var_dump($_POST);
//        die("ok");
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
//                echo "<pre>";
//                var_dump($_SESSION);
//                die("ok");
                return $next ? $this->redirect($next) : $this->goBack();
            }
        }
//        die("no");

        $model->password = '';
        return $this->render('login', [
            'model' => $model, 'next'=>$next
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public
    function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public
    function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public
    function actionFoto()
    {

    }
}
