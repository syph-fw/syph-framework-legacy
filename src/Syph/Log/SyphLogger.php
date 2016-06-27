<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 20/06/2016
 * Time: 17:33
 */

namespace Syph\Log;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Syph\Core\Kernel;
use Syph\DependencyInjection\ServiceInterface;

class SyphLogger implements ServiceInterface{
    
    const DEFAULT_PREFIX_FILE = 'syph-';

    protected $loggers = array();
    protected $logg_default_path;

    /**
     * SyphLogger constructor.
     */
    public function __construct(Kernel $kernel)
    {

        $this->loadLoggerDefaultPath($kernel->getSyphAppLoggDir());
        $this->loadDefaultLogger();

    }
    
    public function getLogger($logg_name = 'syph')
    {
        if(array_key_exists($logg_name,$this->loggers))
        {
            return $this->loggers[$logg_name];
        }else{
            $this->loggers[$logg_name] = new Logger($logg_name);
            $this->loggers[$logg_name]->pushHandler($this->getDefaultHandler());
            return $this->loggers[$logg_name];
        }
    }

    public function getDefaultHandler($full_path = null)
    {
        $path = is_null($full_path)?$this->logg_default_path:$full_path;
        return new StreamHandler($path, Logger::DEBUG);
    }

    private function loadLoggerDefaultPath($app_logg_path)
    {
        $today = new \DateTime();
        $this->logg_default_path = $app_logg_path.DS.self::DEFAULT_PREFIX_FILE.$today->format('Y-m-d').'.log';
    }

    private function loadDefaultLogger()
    {
        $this->loggers['syph'] = $this->getLogger();
    }

    public function getName()
    {
        return 'logger';
    }

    public function __call($method_name,$params)
    {
        try {
            if (method_exists($this->getLogger(), $method_name)) {
                call_user_func_array([$this->getLogger(),$method_name], $params);
            } else {
                sprintf("Method: %s not exist on %s", $method_name, get_class($this));
            }
        }catch (\Exception $e){
            throw $e;
        }

    }

}