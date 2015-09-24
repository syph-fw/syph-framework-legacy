<?php
namespace Syph\Routing;
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 12/08/2015
 * Time: 12:34
 */
class Router
{
    private static $routes = array();

    private function __construct() {}
    private function __clone() {}

    public static function route($pattern, $callback) {
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';

        self::$routes[$pattern] = $callback;
    }

    public static function execute($url) {
        $url = (substr($url,0,1) == '/')? $url : '/'.$url ;
        foreach (self::$routes as $pattern => $callback)
        {
            if (preg_match($pattern, $url, $params))
            {
                array_shift($params);
                return call_user_func_array($callback, array_values($params));
            }
        }
        throw new \Exception(sprintf('Rota: "%s" não encontrada',$url));
    }
}