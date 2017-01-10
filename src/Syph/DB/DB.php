<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 09/01/2017
 * Time: 20:32
 */

namespace Syph\DB;


use ActiveRecord\DatabaseException;
use Syph\Core\Kernel;
use Syph\DB\Exceptions\SyphDatabaseException;
use Syph\DependencyInjection\Container\Container;
use Syph\DependencyInjection\ServiceInterface;

class DB implements ServiceInterface
{
    const SERVICE_NAME = 'db.core';

    private $env;
    private $configs;
    private $kernel;
    private $connector_name_configured;
    private $connector;
    /**
     * @var array|ConnParameters $connections
     */
    private $connections = [];

    /**
     * DB constructor.
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->loadConfigDatabase($kernel->getConfig('database'));

        $this->env = $kernel->getEnv();
        $this->connector = $this->buildConnection();

    }


    private function loadConfigDatabase($configs)
    {
        foreach ($configs as $env => $config) {

            $this->connector_name_configured[$env] = $config['db_connector'];

            if($this->validateConfig($config)){
                $this->configs[$env] = $config;
                $this->connections[$env] = new ConnParameters($env,$config['db'],$config['db_connector'],$config['host'],$config['username'],$config['password'],$config['database']);
            }
        }
    }
    private function validateConfig($configs)
    {
        $required_keys = $this->getRequiredKeyConfigurator();
        foreach ($required_keys as $required_key) {
            if(!array_key_exists($required_key,$configs)){
                throw new SyphDatabaseException(sprintf('%s is required, please set this configuration',$required_key));
            }
        }
        return true;
    }

    private function getRequiredKeyConfigurator()
    {
        return [
            'db_connector',
            'db',
            'host',
            'port',
            'username',
            'password',
            'database'
        ];
    }

    private function buildConnection()
    {
        switch ($this->connector_name_configured[$this->env]){
            case 'active_record':
                $connections = [];
                $modelDirectories = [];
                /**
                 * @var ConnParameters $connection
                 */
                foreach ($this->connections as $connection) {
                    if($connection->getName() == 'active_record'){
                        $connections[$connection->getEnv()] = $connection->getStrToPDO();
                    }
                }

                foreach ($this->kernel->getApps() as $app) {
                    $modelDirectories[] = sprintf('%s%sModel',$app->getPath(),DS);
                }

                $connector = new ARConnector($this->env,$connections,$modelDirectories);
                return $connector;
                break;
        }
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }


}
