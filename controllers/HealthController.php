<?php

namespace app\controllers;

use app\models\Auth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\rest\CreateAction;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\TooManyRequestsHttpException;

/**
 * Default controller for the `reporter` module
 */
class HealthController extends Controller
{
    public function actionCheck(){
        $count = system('netstat -an | grep :' . \Yii::$app->service['port'] . ' | grep ESTABLISHED | wc -l') / 2;
        if($count > 1000){
            throw new HttpException(500, 'Denial of the service');
        } else if($count > 100){
            throw new TooManyRequestsHttpException();
        }
        return '';
    }
}
