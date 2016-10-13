<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 13/10/2016
 * Time: 01:50
 */

namespace Syph\Security\Auth;


use Syph\Core\Events\KernelBootEvent;
use Syph\DependencyInjection\ServiceInterface;
use Syph\EventDispatcher\Interfaces\EventListernerInterface;
use Syph\Http\Session\Session;

class AuthenticationCheker implements EventListernerInterface,ServiceInterface
{
    const SERVICE_NAME = 'security.authentication.checker';
    public function getListeners()
    {
        return ['kernel.boot'=> [$this,'startAuthCheck']];
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }

    public function startAuthCheck(KernelBootEvent $event){
        /**
         * @var Session $session
         */
        $session = $event->getSession();
        if($session->isStart()){
            if($session->has('user_signed')){
            }else{
                $session->set('user_signed',Authentication::AUTH_ROLE_ANONYMOUSLY);
            }
        }
    }
}