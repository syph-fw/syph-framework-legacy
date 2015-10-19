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
    private $routes = array();
    private $matcher = null;
    private $collection = null;

    public function __construct(UrlMatcher $matcher) {
        $this->matcher = $matcher;
        $this->collection = RouterCollection::getAllRoutes();
        $this->loadCollection();
    }

    public function loadCollection(){
        foreach ($this->collection as $name => $route) {
            if($this->matcher->isValidRoute($route))
                $this->addRoute($name,$route);
        }
    }

    public function addRoute($name,Route $route){
        $this->routes[$name] = $route;
    }

    public function match($url){
        return $this->matcher->match($url,$this->routes);

    }

    public function getName()
    {
        return 'routing.router';
    }
}