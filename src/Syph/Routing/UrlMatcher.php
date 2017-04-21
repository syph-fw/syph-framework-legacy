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
    private $internal_redirect = false;

    public function __construct(){
        $this->initPatterns();
    }

    public function matchRoute($collection, $types, $type,$url)
    {
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

//                sd($route->itsBehindTheGate());
                if($route->itsBehindTheGate()){
                    $pass = $this->createAndValidatePass($route);
                    if(!is_null($pass->getRedirectPath())){
                        header(sprintf('Location: %s',$pass->getRedirectPath()));
                        exit();
                    }
                }

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
        return false;
    }

    private function matchRequestType($type, $url, $collections)
    {
        foreach ($collections as $types => $collection)
        {
            if($types == $type || $types == "ANY"){
                $callback = $this->matchRoute($collection,$types,$type,$url);
                if(is_array($callback)) {
                    return $callback;
                }
            }
        }

        throw new \Exception(sprintf('Route: "%s" not found',$url));
    }

    public function match($type,$url,$collections){

        $url = (substr($url,0,1) == '/')? $url : '/'.$url ;
        return $this->matchRequestType($type,$url,$collections);
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

    private function createAndValidatePass(Route $route)
    {
        $pass = new RouteAuthPass();
        $gate = $route->getGate();
        $gate->validatePass($pass);
        if(!$pass->isValid()){
            if(is_null($pass->getRedirectPath())){
                throw new \Exception(sprintf("The route:'%s' needs authentication",$route->getPattern()));
            }
        }
        return $pass;
    }

}