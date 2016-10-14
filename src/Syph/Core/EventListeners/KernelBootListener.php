<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 08/10/2016
 * Time: 12:24
 */

namespace Syph\Core\EventListeners;


use Syph\Core\Events\KernelBootEvent;
use Syph\Core\Interfaces\SyphKernelInterface;
use Syph\DependencyInjection\ServiceInterface;
use Syph\EventDispatcher\Interfaces\EventListernerInterface;
use Syph\Routing\Router;
use Syph\Security\Firewall\Firewall;
use Syph\Security\Firewall\FirewallLoadedEvent;

class KernelBootListener implements EventListernerInterface, ServiceInterface
{

    const SERVICE_NAME = 'kernel.boot.listener';

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getListeners()
    {
        return ['kernel.boot'=>[$this,'runFirewall']];
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }

    public function runFirewall(KernelBootEvent $kernelBootEvent){
        $firewall = new Firewall($kernelBootEvent->getRequest(), $kernelBootEvent->getRouter());
        $kernelBootEvent->setOnContainer($firewall);
        //$kernelBootEvent->getEventDispatcher()->dispatch(Firewall::FIREWALL_LOAD_EVENT, new FirewallLoadedEvent());
    }
}