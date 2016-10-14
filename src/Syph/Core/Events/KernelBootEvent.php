<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 08/10/2016
 * Time: 12:30
 */

namespace Syph\Core\Events;


use Interop\Container\ContainerInterface;
use Syph\DependencyInjection\ServiceInterface;
use Syph\EventDispatcher\EventDispatcher;
use Syph\EventDispatcher\Interfaces\EventInterface;
use Syph\Http\Base\Request;
use Syph\Http\Session\Session;
use Syph\Routing\Router;

class KernelBootEvent implements EventInterface
{

    private $container;

    /**
     * KernelBootEvent constructor.
     * @param $kernel
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        if($this->container->has(Session::SERVICE_NAME)){
            return $this->container->get(Session::SERVICE_NAME);
        }
        return null;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if($this->container->has(Request::SERVICE_NAME)){
            return $this->container->get(Request::SERVICE_NAME);
        }
        return null;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        if($this->container->has(Router::SERVICE_NAME)){
            return $this->container->get(Router::SERVICE_NAME);
        }
        return null;
    }

    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        if($this->container->has(EventDispatcher::SERVICE_NAME)){
            return $this->container->get(EventDispatcher::SERVICE_NAME);
        }
        return null;
    }

    /**
     * @param ServiceInterface $service
     */
    public function setOnContainer(ServiceInterface $service)
    {
        if($this->container->has($service->getName())){
            $this->container->set($service);
        }
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return 'TEST';
    }
}