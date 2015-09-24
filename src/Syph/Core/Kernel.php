<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 19/08/2015
 * Time: 16:25
 */

namespace Syph\Core;

use Syph\AppBuilder\Environment;
use Syph\AppBuilder\Interfaces\BuilderInterface;
use Syph\Http\Interfaces\HttpInterface;
use Syph\Routing\Router;

abstract class Kernel
{
    protected $booted;
    protected $env;
    protected $http;
    protected $app;
    protected $builder;

    public function __construct(Environment $env)
    {
        $this->boot($env);
    }

    private function boot(Environment $env)
    {
        $this->booted = true;
        $this->env = $env;
    }



    public function handleRequest(HttpInterface $http,BuilderInterface $builder)
    {
        $this->http = $http;
        $this->builder = $builder;
        if($this->booted)
            $builder->register($this->env);
    }

    public function getResponse()
    {
        $route = Router::execute($this->http->getRequest());

        if(isset($route['args'])){
            return $this->handleController($route['controller'],$route['action'],$route['args']);
        }else{
            return $this->handleController($route['controller'],$route['action']);
        }

    }

    public function handleController($controllerName,$actionName,$args = array())
    {

        $controllerArr = explode(':',$controllerName);
        $appName = $controllerArr[0];
        if($this->builder->hasApp($appName)) {
            $controllerName = $controllerArr[1];

            $controller_path = APP_REAL_PATH . DS . 'app' . DS . $appName . DS . 'Controller' . DS . $controllerName . '.php';

            $this->app = $this->builder->loadApp($appName);
            
            if (file_exists($controller_path)) {
                include_once($controller_path);

                $controller = '\\' . $appName . '\\' . 'Controller' . '\\' . $controllerName;

                if (method_exists($controller, $actionName)) {

                    return $this->runController($controller, $actionName, $args);
                } else {
                    throw new \Exception(sprintf('Método: %s não encontrado', $actionName), 404);
                }

            } else {
                throw new \Exception(sprintf('Controlador: %s não encontrado', $controllerName), 404);
            }
        }else{
            throw new \Exception(sprintf('Applicação: %s não existe', $appName), 404);
        }
    }

    public function runController($controllerName,$action,$args = array())
    {
        if(is_callable(array($controllerName ,$action)))
        {
            return call_user_func_array(array( new $controllerName ,$action), $args);
        }
        throw new \Exception('Controller não pode ser chamado',404);
    }

}