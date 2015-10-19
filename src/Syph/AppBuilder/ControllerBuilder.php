<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 29/09/2015
 * Time: 23:18
 */

namespace Syph\AppBuilder;


use Syph\Container\Interfaces\ContainerInterface;
use Syph\Container\Interfaces\SyphContainerInterface;
use Syph\Container\SyphContainer;

class ControllerBuilder extends BaseControllerBuilder{

    protected $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    public function getController(){

    }

    public function createController($controller){

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $controller = $this->buildController($class);
        if ($controller instanceof SyphContainerInterface) {
            $controller->setContainer($this->container);
        }

        return array($controller, $method);
    }
} 