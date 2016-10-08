<?php

/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 08/10/2016
 * Time: 00:45
 */
namespace Syph\EventDispatcher\Interfaces;


interface EventDispatcherInterface
{
    public function listen($eventName,$listener);
    public function dispatch($eventName,EventInterface $event);
}