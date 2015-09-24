<?php
namespace Syph\AppBuilder;
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 24/08/2015
 * Time: 14:00
 */




use Syph\Autoload\ClassLoader;
use Syph\AppBuilder\Interfaces\BuilderInterface;

class AppBuilder implements BuilderInterface
{
    private $app_container_config = array();

    public function loadApp($appName)
    {

        $configs = $this->loadConfigApp($this->app_container_config[$appName]["app_path_conf"]);

        return $configs;
    }

    public function loadConfigApp($path){
        if(file_exists($path)){
            return include_once($path);
        }
    }

    public function register(Environment $environment){
        $env = $environment->getEnv();
        foreach($env['packages'] as $k=>$e){
            if($k !== "Syph")
                $this->app_container_config[$k] = array("app_path_conf"=>$e.DS."Config".DS."config.php");
        }

    }

    public function hasApp($appName)
    {
        return array_key_exists($appName,$this->app_container_config);
    }


}