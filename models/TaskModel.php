<?php

namespace app\models;

use app\service\Application;
use Httpful\Request;
use Yii;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property integer $begin
 * @property integer $end
 * @property string $description
 * @property object $reporter
 */
class TaskModel extends \yii\db\ActiveRecord
{
    /**
     * @var array
     */
    public $new = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_tasks');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            ['new', 'each', 'rule' => ['string']],
            ['begin', 'default', 'value' => time()],
            [['begin', 'end'], 'integer'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'begin' => 'Begin',
            'end' => 'End',
            'description' => 'Description',
            'new' => 'New Info'
        ];
    }

    /**
     * @return object
     * @throws BadRequestHttpException
     * @throws MethodNotAllowedHttpException
     */
    public function getReporter(){
        $app = \Yii::$app;
        /* @var $app Application */
        $reportersService = $app->clientAgent->getServices()->filter('service.reporters');
        if(!$reportersService){
            throw new MethodNotAllowedHttpException('Сервис репортеров не отвечает на запросы');
        }
        $response = Request::get('http://' . $reportersService->getAddress() . ':' . $reportersService->getPort() ."/reporters?filter[task_id]={$this->id}")->sendIt();
        Yii::info($response, 'RESPONSE FROM REPORTERS');
        if($response->code != 200 || Json::decode($response->raw_body, false) == null){
            throw new BadRequestHttpException(Json::decode($response->raw_body));
        }
        return Json::decode($response->raw_body, false);
    }
}
