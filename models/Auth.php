<?php

namespace app\models;

use app\service\Application;
use Httpful\Request;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use yii\web\MethodNotAllowedHttpException;

/**
 * This is the model class for table "auth".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $login
 * @property string $password
 * @property object $user
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_auth');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['login', 'password'], 'required'],
            [['login'], 'string', 'max' => 64],
            [['password'], 'string', 'max' => 128],
        ];
    }

    /**
     * Generates password hash by value
     */
    public function generatePassword(){
         $this->password = password_hash($this->password, PASSWORD_BCRYPT, ['salt' => Yii::$app->service['salt']]);
    }

    /**
     * Find auth credentials in DB. Login and non-hashed password value.
     * @return static|null Instance of auth entity.
     */
    public function findIdentity(){
        return self::findOne(['login' => $this->login, 'password' => password_hash($this->password, PASSWORD_BCRYPT, ['salt' => Yii::$app->service['salt']])]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'login' => 'Login',
            'password' => 'Password',
        ];
    }

    /**
     * @return object
     * @throws MethodNotAllowedHttpException
     */
    public function getUser(){
        $app = \Yii::$app;
        /* @var $app Application */
        $userService = $app->clientAgent->getServices()->filter('service.users');
        if(!$userService){
            throw new MethodNotAllowedHttpException('Сервис пользователей не отвечает на запросы');
        }
        $response = Request::put('http://' . $userService->getAddress() . ':' . $userService->getPort() ."/users/{$this->user_id}")->sendIt();
        return Json::decode($response->raw_body, false);
    }
}
