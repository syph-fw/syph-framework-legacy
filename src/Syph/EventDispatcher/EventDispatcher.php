<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 08/10/2016
 * Time: 00:50
 */

namespace Syph\EventDispatcher;


use Syph\DependencyInjection\Container\Container;
use Syph\DependencyInjection\ServiceInterface;
use Syph\EventDispatcher\Exception\NotListedEventException;
use Syph\EventDispatcher\Interfaces\EventDispatcherInterface;
use Syph\EventDispatcher\Interfaces\EventInterface;
use Syph\EventDispatcher\Interfaces\EventListernerInterface;

class EventDispatcher implements EventDispatcherInterface,ServiceInterface
{
    const SERVICE_NAME = "event.dispatcher";

    private $container;
    private $listeners;

    /**
     * EventDispatcher constructor.
     * @param $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function listen($eventName,$listener)
    {
        $this->listeners[$eventName][] = $listener;
    }

    public function addListener(EventListernerInterface $subscriber){
        $listeners = $subscriber->getListeners();
        foreach ($listeners as $eventName => $listener) {
            $this->listen($eventName,$listener);
        }
    }


    public function dispatch($eventName, EventInterface $event)
    {
        //sd($this->listeners);
        if(array_key_exists($eventName,$this->listeners)){

            foreach ($this->listeners[$eventName] as $listener)
            {
                call_user_func_array($listener, array($event));
            }
        }else{
            throw new NotListedEventException($eventName);
        }
    }

    public function loadContainerListeners(){
        $listeners = $this->container->getListeners();
        foreach ($listeners as $listener) {
            $this->addListener($listener);
        }
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }


}