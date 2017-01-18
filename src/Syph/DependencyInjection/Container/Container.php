<?php
namespace Syph\DependencyInjection\Container;
use Interop\Container\ContainerInterface;
use Syph\DependencyInjection\Container\Interfaces\ContainerInterface as SyphContainerInterface;
use Syph\DependencyInjection\ServiceInterface;

/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 26/08/2015
 * Time: 14:47
 */
class Container implements ContainerInterface,SyphContainerInterface
{
    public $service = array();
    public $eventListener = array();

    public function __construct($kernel)
    {
        $this->service['container'] = $this;
        $this->service['kernel'] = $kernel;
    }

    public function startContainer(array $mainServices = array()){
        $this->load($mainServices);
    }

    public function get($name)
    {
        return $this->service[$name];
    }

    public function has($name){
        return array_key_exists($name,$this->service);
    }

    public function set(ServiceInterface $service)
    {
        $this->service[$service->getName()] = $service;
    }

    public function setEventListener(ServiceInterface $service){
        $this->eventListener[$service->getName()] = $service;
    }

    public function debug(){
        foreach ($this->service as $service) {
            echo "<pre>";
            var_dump($service);
            echo "</pre>";
        }
    }

    public function load($services)
    {
        //var_dump($services);
        foreach($services as $name => $service){
            $args = array();
            if(!$this->has($name)){

                if(isset($service['args']) && count($service['args']) > 0){
                    foreach ($service['args'] as $argName=>$arg) {

                        if(is_array($arg)){

                            if(array_key_exists('class',$arg)){
                                $this->load(array($argName=>$arg));
                            }

                        }else{
                            $argName = $arg;
                        }

                        if($this->has($argName)){

                            $args[$name][] = $this->get($argName);
                        }

                    }
                }

                if(array_key_exists('class',$service)){
                    $reflect = new \ReflectionClass($service['class']);
                    $serviceInstance = $reflect->newInstanceArgs((array_key_exists($name, $args)) ? $args[$name] : array());
                    if($serviceInstance instanceof ServiceInterface){
                        $this->set($serviceInstance);
                    }
                    if($service['strategy'] == 'event_listener'){
                        $this->setEventListener($serviceInstance);
                    }
                }


            }
        }
    }

    public function getListeners()
    {
        return $this->eventListener;
    }
}