<?php

namespace app\controllers;

use Yii;
use app\models\Media;
use app\models\UploadMediaForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\TipoCamera;
use yii\helpers\ArrayHelper;



/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends Controller
{
    static $urlMedia = "http://buching.shop/";
    static $pass = "AjejeBrazorf";
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
     * Lists all Media models.
     * @return mixed
     */
    public function actionIndex($password, $idAlbergo)
    {
        if ($password !== MediaController::$pass) {
            return;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Media::find()->where(['idAlbergo' => $idAlbergo]),
        ]);
        //        echo "<pre>";var_dump($dataProvider); die();

        return $this->render('index', [
            'dataProvider' => $dataProvider, 'idAlbergo' => $idAlbergo
        ]);
    }

    /**
     * Displays a single Media model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id), 'idAlbergo'
        ]);
    }

    /**
     * Creates a new Media model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idAlbergo)
    {
        //        $dataProvider = ArrayHelper::map(TipoCamera::find()->select('idTipoCamera,nomeTipoCamera')->asArray()->all(), 'idTipoCamera', 'nomeTipoCamera');

        //        echo "<pre>";var_dump($dataProvider); die();
        //        $dataProvider = array_values($dataProvider);
        $model = new Media();
        $model->idAlbergo = $idAlbergo;
        $up = new UploadMediaForm();
        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($up, 'media');
            //            var_dump($image);
            //            die();
            if ($image) {
                $ext = pathinfo($image->name, PATHINFO_EXTENSION);
                $nameF = Yii::$app->security->generateRandomString() . ".{$ext}";;
                $pathF = 'img/' . $nameF;

                $image->saveAs($pathF);
                $model->urlMedia = Mediacontroller::$urlMedia . $pathF;
                //                var_dump($image);die();
                if (!$model->save()) {
                    var_dump($model);
                    die("errore");
                }
                return $this->redirect(['view', 'id' => $model->idMedia]);
            }
        }
        return $this->render('create', [
            'model' => $model, 'up' => $up, 'data' => TipoCamera::find()->all()
        ]);
    }

    /**
     * Updates an existing Media model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $up = new UploadMediaForm();

        //        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //            return $this->redirect(['view', 'id' => $model->idMedia]);
        //        }
        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($up, 'media');
            //            var_dump($image);
            //            die();
            if ($image) {
                $ext = pathinfo($image->name, PATHINFO_EXTENSION);
                $nameF = Yii::$app->security->generateRandomString() . ".{$ext}";;
                $pathF = 'img/' . $nameF;

                $image->saveAs($pathF);
                $model->urlMedia = MediaController::$urlMedia . $pathF;
                //                var_dump($image);die();
                if (!$model->save()) {
                    var_dump($model);
                    die("errore");
                }
                return $this->redirect(['view', 'id' => $model->idMedia]);
            }
        }
        return $this->render('update', [
            'model' => $model, 'up' => $up, 'data' => TipoCamera::find()->all()
        ]);
    }

    /**
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete($id)
    {
        $m =  $this->findModel($id);
        $m->delete();
        return $this->redirect(['index','password'=>MediaController::$pass,'idAlbergo'=>$m->idAlbergo]);
    }
    /**
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionGettipi()
    {
        $data = ArrayHelper::map(TipoCamera::find()->select('idTipoCamera,nomeTipoCamera')->asArray()->all(), 'idTipoCamera', 'nomeTipoCamera');

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $out['results'] = array_values($data);
        return $out;
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
