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
class EventsController extends ActiveController
{
    public $modelClass = 'app\models\EventModel';

    public function actions(){
        return @end(ArrayHelper::filter(['a' => parent::actions()], ['a', '!a.create']));
    }

    public function actionCreate(){
        $baseAction = new CreateAction('eventsCreate', $this, [
            'modelClass' => $this->modelClass
        ]);
        /* @var $model EventModel */
        $model = $baseAction->run();
        $app = \Yii::$app;
        /* @var $app Application */
        $tasksService = $app->clientAgent->getServices()->filter('service.tasks');
        if(!$tasksService){
            throw new MethodNotAllowedHttpException('Сервис заданий недоступен.');
        }
        $response = Request::post('http://' . $tasksService->getAddress() . ':' . $tasksService->getPort() .
            "/tasks", Json::encode([
                'description' => $model->task_description
        ]), Mime::JSON)->sendIt();
        \Yii::info([$response, Json::decode($response->raw_body, false)], 'TASK ANSWER');
        if($response->code != 200 || ($task = Json::decode($response->raw_body)) === null){
            \Yii::$app->response->statusCode = $response->code;
            return Json::decode($response->raw_body);
        }
        return $model;
    }
}
