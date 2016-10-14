<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 14/10/2016
 * Time: 15:01
 */

namespace Syph\EventDispatcher\Exception;


class NotListedEventException extends \Exception
{
    public function __construct($eventName)
    {
        parent::__construct(sprintf('Not listended %s event on application',$eventName),500);
    }
}