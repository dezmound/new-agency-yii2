<?php
/**
 * Created by PhpStorm.
 * User: dez
 * Date: 23.11.17
 * Time: 12:17
 */

namespace app\service;

use BR\Consul\Agent;
use BR\Consul\Model\Service;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class Application extends \yii\web\Application {

    /**
     * @var string Consul connection 'http://localhost:8500'
     */
    public $consul = '';

    /**
     * @var array Information about service, such as [
     *  'port' => 3308 // Service port,
     *  'name' => 'service1' // Service name,
     *  'tags' => ['some', 'canDoThis'] // Array of tags,
     *  'id' => 'service-1' // Unique ID of service
     * ]
     */
    public $service = [];

    /**
     * @var null|Service Instance of service.
     */
    private $_service = null;

    /**
     * @var null|Agent Instance of consul agent connection
     */
    public $clientAgent = null;

    public function init(){
        $this->clientAgent = new Agent($this->consul);
        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * Register service application in consul.
     * @throws InvalidConfigException|Exception
     */
    public function register(){

        if(!$this->consul)
            return;
        if(empty($this->service) || !isset($this->service['port']) || !intval($this->service['port']) || !isset($this->service['id'])){
            throw new InvalidConfigException('Invalid configuration for service, id and port are required');
        }
        $this->_service = new Service();
        $this->_service->setName($this->service['name'] ?? preg_replace('/^[\d- _]+|[\d- _]+$/ui', '', $this->service['id']) );
        $this->_service->setPort(intval($this->service['port']));
        $this->_service->setId($this->service['id']);
        $this->_service->setAddress($this->service['ip'] ?? '127.0.0.1');
        $this->_service->setTags($this->service['tags'] ?? []);
        $this->_service->setCheck([
            'name' => $this->service['id'] . ' responds at port ' . $this->service['port'],
            'http' => 'http://' . ($this->service['ip'] ?? '127.0.0.1') . ':' . $this->service['port'] . '/health/check',
            'interval' => '10s'
        ]);


        if(!$this->clientAgent->registerService($this->_service)){
            throw new Exception('Could not register service in consul');
        }
        echo 'Successfully register service as ' . $this->service['id'] . PHP_EOL;
    }

    /**
     * Deregister service from consul catalog.
     */
    public function deregister(){
        $this->clientAgent->deregisterService($this->service['id']);
    }
}