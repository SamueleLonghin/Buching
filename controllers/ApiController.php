<?php

namespace app\controllers;

class ApiController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpload(){

        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('index'));
        }
        $model = $Id == -1 ? new Film() : Film::getById($Id);
        if (($Id == -1 || !isset($model->Id))) {
            $model->ImageC = 'https://votapp.space/img/VAdsQPEECU4s4nMgrzC9_Q5GzMdhmclD.jpg';
            $model->Id = -1;
        }

        if ($model->load(Yii::$app->request->post())) {
            if (!Yii::$app->User->identity->canEdit()) {
                Yii::$app->session->setFlash('error', 'Non sei abilitato a modificare');
//                return $this->goBack();
            } else {
                $image = UploadedFile::getInstance($model, 'FileImmagine');
                if ($image) {
                    $ext = pathinfo($image->name, PATHINFO_EXTENSION);
                    $nameF = Yii::$app->security->generateRandomString() . ".{$ext}";;
                    $pathF = 'img/' . $nameF;

                    $image->saveAs($pathF);

                    //Immagine grande
                    $nameG = Yii::$app->security->generateRandomString() . ".jpg";;
                    $pathG = 'img/' . $nameG;

                    $imagickG = new \Imagick($pathF);
                    $imagickG = $imagickG->flattenImages();
                    $imagickG->adaptiveResizeImage(968, 1368, true);
                    $imagickG->setCompressionQuality(1);
                    $imagickG->setImageFormat('jpg');
                    $imagickG->writeImage($pathG);

                    //Immagine piccola
                    $nameP = Yii::$app->security->generateRandomString() . ".jpg";;
                    $pathP = 'img/' . $nameP;

                    $imagickP = new \Imagick($pathF);
                    $imagickP = $imagickP->flattenImages();
                    $imagickP->adaptiveResizeImage(154, 218, true);
                    $imagickP->setCompressionQuality(1);
                    $imagickP->setImageFormat('jpg');
                    $imagickP->writeImage($pathP);

                    //elimino immagine originale
                    unlink($pathF);

//                Film::compressImage($fullPathC, $fullPathC, 10);

                    $model->ImageF = "https://votapp.barsanti.edu.it/" . $pathG;
                    $model->ImageC = "https://votapp.barsanti.edu.it/" . $pathP;
//                var_dump($model);die();
                }
                if ($model->validate() && $model->Salva()) {
                    return $this->redirect(Url::toRoute(['gestioneevento', 'Id' => $model->IdEvento, '#' => $model->Id]));
                }
            }
        }
    }
}
