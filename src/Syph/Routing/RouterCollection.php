<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 10/10/2015
 * Time: 14:20
 */

namespace Syph\Routing;


use Syph\DependencyInjection\ServiceInterface;
use Syph\Routing\Exceptions\RouterException;

class RouterCollection implements ServiceInterface{


    const SERVICE_NAME = 'routing.router_collection';
    private $routes;
    private $groups;

    private static $static_routes;
    private static $static_groups;
    private static $stack_group = [];

    public function __construct(){}

    public function __clone(){}

    public static function route($name, Route $route) {
        self::$static_routes[$name] = $route;
        $route->setRequestType('ANY');
    }

    public static function getAllRoutes() {
        return self::$static_routes;
    }

    public static function getAllGroups() {
        return self::$static_groups;
    }

    public static function getGroupStack()
    {
        return self::$stack_group;
    }

    public function getRoutes() {
        return $this->routes;
    }

    public function getGroups() {
        return $this->groups;
    }

    public function getRoute($name){
        return $this->routes[$name];
    }

    public function getGroup($name)
    {
        return $this->groups[$name];
    }

    public function add($firstParam,$secondParam)
    {
        $this->addAny($firstParam, $secondParam);
    }

    public function addGet($firstParam,$secondParam)
    {
        $route = new Route();
        $route->setRequestType('GET');
        $this->handleRouteParams($route,$firstParam, $secondParam, 'GET');
        $this->handleToAdd($route);
    }

    public function addPost($firstParam,$secondParam)
    {
        $route = new Route();
        $route->setRequestType('POST');
        $this->handleRouteParams($route,$firstParam, $secondParam, 'POST');
        $this->handleToAdd($route);
    }

    public function addAny($firstParam,$secondParam)
    {
        $route = new Route();
        $route->setRequestType('ANY');
        $this->handleRouteParams($route,$firstParam, $secondParam);
        $this->handleToAdd($route);
    }

    /**
     * @param $firstParam
     * @param \Closure $clojureParam
     * @return $this
     */
    public function group($firstParam,\Closure $clojureParam)
    {
        /**
         * @var RouterCollection $routes
         * @var Route $route
         */
        $group = new RouteGroup();
        $this->handleGroupParams($group, $firstParam);
        $this->setGroupOnRouteCollection($group);

        $this->addGroupOnStack($group);
        $clojureParam($this);
        $this->removeLastGroupOnStack();
        return $this;
    }

    private function handleGroupParams(RouteGroup $group, $firstParam)
    {
        $this->handleFirstParamGroup($group,$firstParam);
    }

    private function handleFirstParamGroup(RouteGroup $group, $firstParam)
    {
        $name = substr( md5(rand()),0, 7);

        if(is_array($firstParam)){

            if(array_key_exists('name',$firstParam)){
                $name = $firstParam['name'];
            }

            if(!array_key_exists('path',$firstParam)){
                throw new RouterException('Group not has pattern param');
            }

            $pattern = $firstParam['path'];

        }else{
            $pattern = $firstParam;
        }

        if(!empty(self::$stack_group)){
            $group->setGroup(end(self::$stack_group));
        }

        $group->setPattern($pattern);
        $group->setName($name);
    }

    public function handleRouteParams(Route $route, $firstParam, $secondParam, $type = 'ANY')
    {
        $this->handleFirstParamRoute($route, $firstParam);
        $this->handleSecondParamRoute($route, $secondParam);
    }

    private function handleToAdd(Route $route)
    {
        if(!empty(self::$stack_group)){
            $this->addGroupOnRoute($route,end(self::$stack_group));
        }
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
        $this->routes[$route->getRequestType()][$route->getName()] = $route;
        self::$static_routes[$route->getRequestType()][$route->getName()] = $route;
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

    private function setGroupOnRoute(RouteGroup $group, $routeCollection)
    {
        /**
         * @var Route $route
         */
        foreach ($routeCollection as $route) {
            $route->setGroup($group);
        }
    }

    private function setGroupOnRouteCollection(RouteGroup $routeGroup)
    {
        $this->groups[$routeGroup->getName()] = $routeGroup;
        self::$static_groups[$routeGroup->getName()] = $routeGroup;
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }

    private function addGroupOnStack(RouteGroup $routeGroup)
    {
        self::$stack_group[] = $routeGroup;
    }
    private function removeLastGroupOnStack()
    {
        array_pop(self::$stack_group);
    }
    private function addGroupOnRoute(Route $route,RouteGroup $group)
    {
        $route->setGroup($group);
    }



}