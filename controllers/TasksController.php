<?php

namespace app\controllers;

use app\models\TaskModel;
use app\models\TaskNewModel;
use app\service\Application;
use Httpful\Mime;
use Httpful\Request;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\rest\CreateAction;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

class TasksController extends ActiveController
{
    public $modelClass = 'app\models\TaskModel';


    public function actions(){
        return @end(ArrayHelper::filter(['a' => parent::actions()], ['a', '!a.create']));
    }

    public function actionCreate(){
        $baseAction = new CreateAction('eventsCreate', $this, [
            'modelClass' => $this->modelClass
        ]);
        /* @var $model TaskModel */
        $model = $baseAction->run();
        $app = \Yii::$app;
        /* @var $app Application */
        $reportersService = $app->clientAgent->getServices()->filter('service.reporters');
        if(!$reportersService){
            throw new MethodNotAllowedHttpException('Сервис репортеров недоступен.');
        }
        $response = Request::get('http://' . $reportersService->getAddress() . ':' . $reportersService->getPort() .
            "/reporters?filter[task_id]=NULL")->sendIt();
        \Yii::info($response->raw_body, 'REPORTERS_RESPONSE');
        if($response->code != 200 || ($reportersList = Json::decode($response->raw_body, false)) === null){
            \Yii::$app->response->statusCode = $response->code;
            return Json::decode($response->raw_body) ?: $response->raw_body;
        }
        \Yii::info($reportersList, 'REPORTERS_LIST');
        if(empty($reportersList)){
            throw new MethodNotAllowedHttpException('В данный момент все репортеры заняты');
        }
        $reporter = is_array($reportersList) ? end($reportersList) : $reportersList;
        $response = Request::put('http://' . $reportersService->getAddress() . ':' . $reportersService->getPort() .
            "/reporters/{$reporter->id}", Json::encode([
                'task_id' => $model->id
        ]), Mime::JSON)->sendIt();
        \Yii::info($response->raw_body, 'REPORTER_UPDATE');
        if($response->code != 200 || ($reportersList = Json::decode($response->raw_body)) === null){
            \Yii::$app->response->statusCode = $response->code;
            return Json::decode($response->raw_body) ?: $response->raw_body;
        }
        return $model;
    }


    /**
     * /tasks/close?id=<id>&new[short]=<short>&new[full]=<full>
     * @return TaskModel|array|\Httpful\Response|null|static|string
     * @throws BadRequestHttpException
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     */
    public function actionClose(){
        $model = new TaskModel();
        \Yii::info(ArrayHelper::merge(\Yii::$app->request->queryParams, \Yii::$app->request->bodyParams), 'INPUT VALUES');
        if($model->load(ArrayHelper::merge(\Yii::$app->request->queryParams, \Yii::$app->request->bodyParams), '') && $model->validate()){
            \Yii::info($model, 'MODEL');
            if(!($model = TaskModel::findOne(['id' => $model->id]))){
                throw new NotFoundHttpException('Задача с заданным id отсутствует в базе');
            }
            $model->load(ArrayHelper::merge(\Yii::$app->request->queryParams, \Yii::$app->request->bodyParams), '');
            if(!$model->new || !($model->new = new TaskNewModel($model->new))->validate()){
                throw new BadRequestHttpException('Отсутсвует описание для новости, соответсвующей этому заданию');
            }
            $app = \Yii::$app;
            /* @var $app Application */
            $reportersService = $app->clientAgent->getServices()->filter('service.reporters');
            if(!$reportersService){
                throw new MethodNotAllowedHttpException('Сервис репортеров недоступен.');
            }
            $reporter = $model->reporter;
            $reporter = is_array($reporter) ? end($reporter) : $reporter;
            \Yii::info($reporter, 'REPORTER IS');
            $response = Request::patch('http://' . $reportersService->getAddress() . ':' . $reportersService->getPort() .
                "/reporters/{$reporter->id}", Json::encode([
                    'task_id' => NULL
            ]), Mime::JSON)->sendIt();
            $model->end = time();
            if($response->code != 200 || Json::decode($response->raw_body) === null){
                \Yii::$app->response->statusCode = $response->code;
                return Json::decode($response->raw_body) ?: $response->raw_body;
            }

            $newsService = $app->clientAgent->getServices()->filter('service.news');
            if(!$reportersService){
                throw new MethodNotAllowedHttpException('Сервис новостей недоступен.');
            }

            $response = Request::post('http://' . $newsService->getAddress() . ':' . $newsService->getPort() .
                "/news", Json::encode([
                'short' => $model->new->short,
                'full' => $model->new->full,
                'theme' => $model->new->theme
            ]), Mime::JSON)->sendIt();

            if($response->code != 200 || Json::decode($response->raw_body) === null){
                \Yii::$app->response->statusCode = $response->code;
                return Json::decode($response->raw_body) ?: $response->raw_body;
            }

            if($model->save()){
                return $model;
            }
            return $model->getErrors();
        }
        throw new BadRequestHttpException($model->getErrors());
    }

}
