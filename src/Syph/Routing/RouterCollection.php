<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 10/10/2015
 * Time: 14:20
 */

namespace Syph\Routing;


class RouterCollection {


    private static $routes;

    private function __construct(){}
    private function __clone(){}

    public static function route($name, $route) {
        self::$routes[$name] = $route;
    }

    public static function getAllRoutes() {
        return self::$routes;
    }

    public static function getRoute($name){
        return self::$routes[$name];
    }
} 