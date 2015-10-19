<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 10/10/2015
 * Time: 14:00
 */

namespace Syph\Routing;


use Syph\DependencyInjection\ServiceInterface;

class UrlMatcher implements ServiceInterface{

    public function match($url,$collection){

        $url = (substr($url,0,1) == '/')? $url : '/'.$url ;
        foreach ($collection as $name => $route)
        {
            $pattern = '/^' . str_replace('/', '\/', $route->getPattern()) . '$/';
            if (preg_match($pattern, $url, $params))
            {
                array_shift($params);

                return call_user_func_array($route->getCallback(), array_values($params));
            }
        }
        throw new \Exception(sprintf('Route: "%s" not found',$url));

    }

    public function isValidRoute(Route $route){
        return $route->getPattern() && is_callable($route->getCallback());
    }

    public function getName()
    {
        return "routing.urlmatcher";
    }
}