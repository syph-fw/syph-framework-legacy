<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 08/10/2016
 * Time: 12:24
 */

namespace Syph\Core\EventListeners;


use Syph\Core\Events\KernelBootEvent;
use Syph\Core\Events\RequestStartEvent;
use Syph\Core\Interfaces\SyphKernelInterface;
use Syph\DependencyInjection\ServiceInterface;
use Syph\EventDispatcher\Interfaces\EventListernerInterface;
use Syph\Http\Base\Request;
use Syph\Http\Response\Response;
use Syph\Routing\Router;

class RequestStartListener implements EventListernerInterface, ServiceInterface
{

    const SERVICE_NAME = 'request.start.listener';

    private $request;
    private $router;

    public function __construct()
    {
    }

    public function getListeners()
    {
        return ['request.handle'=>[$this,'runFirewall']];
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }

    public function runFirewall(RequestStartEvent $requestStartEvent){
        $this->request = $requestStartEvent->getRequest();
        $this->router = $requestStartEvent->getRouter();
    }
}