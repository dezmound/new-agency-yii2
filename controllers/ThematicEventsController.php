<?php

namespace app\controllers;

use app\models\EventModel;
use app\service\Application;
use Httpful\Mime;
use Httpful\Request;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\rest\CreateAction;
use yii\web\MethodNotAllowedHttpException;

/**
 * Default controller for the `news` module
 */
class ThematicEventsController extends ActiveController
{
    public $modelClass = 'app\models\EventModel';

    public function actions(){
        return @end(ArrayHelper::filter(['a' => parent::actions()], ['a', '!a.create', '!a.delete', '!a.update']));
    }

    public function actionCreate(){
        $app = \Yii::$app;
        /* @var $app Application */
        $eventsService = $app->clientAgent->getServices()->filter('service.events');
        if(!$eventsService){
            throw new MethodNotAllowedHttpException('Сервис событий недоступен.');
        }
        $response = Request::post('http://' . $eventsService->getAddress() . ':' . $eventsService->getPort() .
            "/events", Json::encode(ArrayHelper::merge(\Yii::$app->request->queryParams,
            \Yii::$app->request->bodyParams)), Mime::JSON)->sendIt();
        \Yii::$app->response->statusCode = $response->code;
        return Json::decode($response->raw_body);
    }

    public function actionDelete(){
        $app = \Yii::$app;
        /* @var $app Application */
        $eventsService = $app->clientAgent->getServices()->filter('service.events');
        if(!$eventsService){
            throw new MethodNotAllowedHttpException('Сервис событий недоступен.');
        }
        $response = Request::delete('http://' . $eventsService->getAddress() . ':' . $eventsService->getPort() .
            "/events/" . \Yii::$app->request->get('id'))->sendIt();
        \Yii::$app->response->statusCode = $response->code;
        return Json::decode($response->raw_body);
    }

    public function actionUpdate(){
        $app = \Yii::$app;
        /* @var $app Application */
        $eventsService = $app->clientAgent->getServices()->filter('service.events');
        if(!$eventsService){
            throw new MethodNotAllowedHttpException('Сервис событий недоступен.');
        }
        $response = Request::put('http://' . $eventsService->getAddress() . ':' . $eventsService->getPort() .
            "/events/" . \Yii::$app->request->get('id'), Json::encode(ArrayHelper::merge(\Yii::$app->request->queryParams,
            \Yii::$app->request->bodyParams)), Mime::JSON)->sendIt();
        \Yii::$app->response->statusCode = $response->code;
        return Json::decode($response->raw_body);
    }

    public function actionTheme(){
        $app = \Yii::$app;
        /* @var $app Application */
        $newsService = $app->clientAgent->getServices()->filter('service.news');
        if(!$newsService){
            throw new MethodNotAllowedHttpException('Сервис новостей недоступен.');
        }
        $response = Request::get('http://' . $newsService->getAddress() . ':' . $newsService->getPort() .
            "/news?filter[theme]=" . \Yii::$app->request->get('theme'))->sendIt();
        \Yii::info($response, 'RESPONSE FROM NEWS');
        \Yii::$app->response->statusCode = $response->code;
        return Json::decode($response->raw_body);
    }

}
