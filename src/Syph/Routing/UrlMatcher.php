<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 10/10/2015
 * Time: 14:00
 */

namespace Syph\Routing;


use Syph\DependencyInjection\ServiceInterface;
use Syph\Routing\Exceptions\RouterException;

class UrlMatcher implements ServiceInterface{

    private $patterns;
    private $route_match;
    public function __construct(){
        $this->initPatterns();
    }

    public function match($type,$url,$collections){

        $url = (substr($url,0,1) == '/')? $url : '/'.$url ;

        foreach ($collections as $types => $collection)
        {
            if($types == $type || $types == "ANY"){
                /**
                 * @var Route $route
                 */
                foreach ($collection as $name => $route)
                {
                    $pattern = '/^' . str_replace('/', '\/', $route->getPattern()) . '$/';

                    if (preg_match($pattern, $url, $params))
                    {
                        array_shift($params);
                        $this->route_match = $route;

                        if($types == $type && $type !== $route->getRequestType() && $route->getRequestType() != 'ANY'){
                            throw new RouterException('This route, not allowed this request type');
                        }

                        if($route->isStringReference()){
                            $route->fillParams($params);
                            $controller = $route->getStringController();
                            $args = $route->getFilledParams();
                            $callback = function () use ($controller,$args){
                                return ['controller' => $controller,'args'=>$args];
                            };

                        }else{
                            $callback = $route->getCallback();
                        }

                        return call_user_func_array($callback, array_values($params));
                    }
                }
            }
        }
        throw new \Exception(sprintf('Route: "%s" not found',$url));

    }

    public function reverse($selected_route,$collection, array $parameters = array()){
        foreach ($collection as $name => $route)
        {
            if($selected_route == $name){
                $url = $route->getPattern();

                if(count($parameters) > 0){
                    $url = preg_replace($this->patterns, $parameters, $url);
                }

                return $url;
            }
        }
        throw new \Exception(sprintf('Route with name: "%s" not found',$selected_route));
    }

    public function isValidRoute(Route $route){
//        return $route->getPattern() && is_callable($route->getCallback());
        return $route->getPattern();
    }

    public function getRouteMatch()
    {
        return $this->route_match;
    }

    public function getName()
    {
        return "routing.urlmatcher";
    }

    private function initPatterns()
    {
        $this->patterns = array(
            '/\(\\\w\+\)/',
            '/\(\\\d\+\)/',
        )
        ;
    }
}