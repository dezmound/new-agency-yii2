<?php

namespace app\controllers;

use app\models\Auth;
use app\service\Application;
use Httpful\Request;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\rest\CreateAction;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `reporter` module
 */
class AuthController extends ActiveController
{
    public $modelClass = 'app\models\Auth';

    public function actions(){
        return @end(ArrayHelper::filter(['a' => parent::actions()], ['a', '!a.create']));
    }

    public function actionSignUp(){
        $baseAction = new CreateAction('authCreate', $this, [
            'modelClass' => $this->modelClass
        ]);
        /* @var $model Auth */
        $model = $baseAction->run();
        $model->generatePassword();
        $model->save();
        return $model;
    }

    public function actionLogin(){
        $auth = new Auth();
        if($auth->load(\Yii::$app->request->bodyParams, '') && $auth->validate()){
            if(($auth = $auth->findIdentity())){
                $app = \Yii::$app;
                /* @var $app Application */
                $tokenService = $app->clientAgent->getServices()->filter('service.tokens');
                if(!$tokenService){
                    throw new MethodNotAllowedHttpException('Сервис токенов недоступен.');
                }
                $response = Request::put('http://' . $tokenService->getAddress() . ':' . $tokenService->getPort() .
                "/tokens/generate?auth_id={$auth->id}")->sendIt();
                if($response->code != 200 || !Json::decode($response->raw_body)){
                    \Yii::$app->response->statusCode = $response->code;
                    return Json::encode($response->raw_body);
                }
                return Json::decode($response->raw_body);
            }
            throw new NotFoundHttpException("Неверное имя пользователя или пароль");
        }
        throw new BadRequestHttpException("Неправильный формат запроса login & password обязательны.");
    }

    public function actionUser(){
        if($token = \Yii::$app->request->getQueryParam('token')){
            $app = \Yii::$app;
            /* @var $app Application */
            $tokenService = $app->clientAgent->getServices()->filter('service.tokens');
            if(!$tokenService){
                throw new MethodNotAllowedHttpException('Сервис токенов недоступен.');
            }
            $response = Request::put('http://' . $tokenService->getAddress() . ':' . $tokenService->getPort() .
                "/tokens/validate?token={$token}")->sendIt();
            if($response->code == 200 && ($token = Json::decode($response->raw_body, false)) && isset($token->auth_id)){
                return (Auth::findOne([
                    'id' => $token->auth_id,
                ]))->user;
            }
            throw new NotFoundHttpException("Пользователь по заданному токену не найден");
        }
        throw new BadRequestHttpException("Неправильный формат запроса");
    }
}
