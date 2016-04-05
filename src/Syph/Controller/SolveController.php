<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 05/10/2015
 * Time: 22:58
 */

namespace Syph\Controller;


use Syph\DependencyInjection\Container\Container;
use Syph\DependencyInjection\Container\Interfaces\SyphContainerInterface;
use Syph\DependencyInjection\Container\SyphContainer;
use Syph\DependencyInjection\ServiceInterface;
use Syph\Http\Base\Request;


class SolveController implements ServiceInterface{
    protected $container;
    protected $parser;

    public function __construct(Container $container,ParseController $parser)
    {
        $this->container = $container;
        $this->parser = $parser;
    }

    public function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            $count = substr_count($controller, ':');
            if (2 == $count) {
                // controller in the a:b:c notation then
                $controller = $this->parser->parse($controller);
            } elseif (1 == $count) {
                // controller in the service:method notation
                list($service, $method) = explode(':', $controller, 2);

                return array($this->container->get($service), $method);
            } elseif ($this->container->has($controller) && method_exists($service = $this->container->get($controller), '__invoke')) {
                return $service;
            } else {
                throw new \Exception(sprintf('Unable to parse the controller name "%s".', $controller));
            }
        }

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \Exception(sprintf('Class "%s" does not exist.', $class));
        }

        $controller = $this->instantiateController($class);
        if ($controller instanceof SyphContainer) {
            $controller->setContainer($this->container);
        }

        return array($controller, $method);
    }

    private function instantiateController($class)
    {
        return new $class();
    }

    public function getController(Request $request)
    {
        if (!$controller = $request->attributes->get('controller')) {
            return false;
        }


        $callable = $this->createController($controller);

        if (!is_callable($callable)) {
            throw new \Exception(sprintf('Controller "%s" for URI "%s" is not callable.', $controller, $request->getPathInfo()));
        }

        return $callable;
    }

    public function getArgs(Request $request, $controller)
    {
        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof \Closure) {
            $r = new \ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } else {
            $r = new \ReflectionFunction($controller);
        }

        return $this->doGetArgs($request, $controller, $r->getParameters());
    }

    protected function doGetArgs(Request $request, $controller, array $parameters)
    {
        $attributes = $request->attributes->getAll();
        $arguments = array();
        foreach ($parameters as $param) {

            if (array_key_exists($param->name, $attributes['args'])) {
                $arguments[] = $attributes['args']  [$param->name];
            } elseif ($param->getClass() && $param->getClass()->isInstance($request)) {
                $arguments[] = $request;
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                if (is_array($controller)) {
                    $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                } elseif (is_object($controller)) {
                    $repr = get_class($controller);
                } else {
                    $repr = $controller;
                }

                throw new \Exception(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $repr, $param->name));
            }
        }

        return $arguments;
    }

    public function getName()
    {
        return 'controller.solve';
    }
}