<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 10/10/2015
 * Time: 14:20
 */

namespace Syph\Routing;


use Syph\Routing\Exceptions\RouterException;

class RouterCollection {


    private static $routes;

    public function __construct(){}
    public function __clone(){}

    public static function route($name, Route $route) {
        self::$routes[$name] = $route;
        $route->setRequestType('ANY');
    }

    public static function getAllRoutes() {
        return self::$routes;
    }

    public static function getRoute($name){
        return self::$routes[$name];
    }

    public function add($firstParam,$secondParam)
    {
        $this->addAny($firstParam, $secondParam);
    }

    public function addGet($firstParam,$secondParam)
    {
        $route = new Route();
        $this->handleParams($route,$firstParam, $secondParam);
        $route->setRequestType('GET');
    }

    public function addPost($firstParam,$secondParam)
    {
        $route = new Route();
        $this->handleParams($route,$firstParam, $secondParam);
        $route->setRequestType('POST');
    }

    public function addAny($firstParam,$secondParam)
    {
        $route = new Route();
        $this->handleParams($route,$firstParam, $secondParam);
        $route->setRequestType('ANY');
    }

    public function handleParams(Route $route,$firstParam,$secondParam)
    {
        $this->handleFirstParamRoute($route, $firstParam);
        $this->handleSecondParamRoute($route, $secondParam);
        $this->addRouteOnCollection($route);
    }

    private function handleFirstParamRoute(Route $route,$firstParam){
        $name = substr( md5(rand()),0, 7);

        if(is_array($firstParam)){

            if(array_key_exists('name',$firstParam)){
                $name = $firstParam['name'];
            }

            if(!array_key_exists('path',$firstParam)){
                throw new RouterException('Route not has pattern param');
            }

            $pattern = $firstParam['path'];

        }else{
            $pattern = $firstParam;
        }

        $params = $this->getParamsOfPattern($pattern);
        $route->setParams($params);

        $pattern = $this->handlePattern($route,$pattern);
        $route->setPattern($pattern);

        $route->setName($name);

    }

    private function handleSecondParamRoute(Route $route,$secondParam){
        if(!is_callable($secondParam)){
            $route->setStringReference(true)->setStringController($secondParam);
        }else{
            $route->setCallback($secondParam);
        }
    }

    private function getParamsOfPattern($pattern)
    {
        $matches = [];
        $params = [];
        preg_match_all('#\{\w+\}#', $pattern, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

        foreach ($matches as $match) {
            $varName = substr($match[0][0], 1, -1);
            $params[] = $varName;
        }

        return $params;
    }

    private function addRouteOnCollection(Route $route)
    {
        self::$routes[$route->getName()] = $route;
    }

    private function handlePattern(Route $route, $pattern)
    {
        $newPattern = $pattern;
        $replace = '(\w+)';
        foreach ($route->getParams() as $param) {
            $reg = sprintf('/\{%s\}/',$param);
            $newPattern = preg_replace($reg,$replace , $newPattern);
        }
        $route->setOrigPattern($pattern);
        return $newPattern;
    }

} 