<?php
namespace Syph\Routing;
use Syph\DependencyInjection\ServiceInterface;

/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 12/08/2015
 * Time: 12:34
 */
class Router implements ServiceInterface
{
    const SERVICE_NAME = 'routing.router';
    private $routes = array();
    /**
     * @var null|UrlMatcher
     */
    private $matcher = null;
    private $collection = null;
    private $status;

    public function __construct(UrlMatcher $matcher) {
        $this->matcher = $matcher;
        $this->collection = RouterCollection::getAllRoutes();
        $this->loadGlobalCollection();
        $this->loadStatus();
    }

    public function loadGlobalCollection(){
        foreach ($this->collection as $type => $collection) {
            $this->loadCollection($collection,$type);
        }
    }

    public function loadCollection($collection,$type){

        if(!$this->checkCollection())
            return;

        foreach ($collection as $name => $route) {
            if($this->matcher->isValidRoute($route))
                $this->addRoute($name,$type,$route);
        }

    }

    public function addRoute($name,$type,Route $route){
        $this->routes[$type][$name] = $route;
    }

    public function match($type,$url){
        return $this->matcher->match($type,$url,$this->routes);

    }

    public function reverse($name,array $parameters = array()){
        return $this->matcher->reverse($name,$this->routes,$parameters);
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }

    public function loadStatus()
    {
        if($this->checkCollection()) {
            $this->setStatus('off');
            return;
        }

        $this->setStatus('on');
    }

    public function checkCollection(){
        return !is_null($this->collection);
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function getMatcher(){
        return $this->matcher;
    }
}