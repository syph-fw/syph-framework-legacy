<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 12/10/2016
 * Time: 21:56
 */

namespace Syph\Security\Firewall;


use Syph\Core\Events\RequestStartEvent;
use Syph\DependencyInjection\ServiceInterface;
use Syph\EventDispatcher\Interfaces\EventListernerInterface;

class FirewallProvider implements EventListernerInterface,ServiceInterface
{
    const SERVICE_NAME = 'firewall.provider';
    private $router;
    private $request;
    private $firewall;

    /**
     * FirewallProvider constructor.
     */
    public function __construct()
    {
    }


    public function getName()
    {
        return self::SERVICE_NAME;
    }

    public function getListeners()
    {
        return [
            'request.handle' => [$this,'build']
        ];
    }

    public function build(RequestStartEvent $event)
    {
        //$this->firewall = new Firewall($event->getRequest(),$event->getRouter());
    }
}