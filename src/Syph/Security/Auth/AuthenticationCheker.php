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
    private $user_signed;

    public function getListeners()
    {
        return ['kernel.boot'=> [$this,'startAuthCheck']];
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }

    /**
     * @param KernelBootEvent $event
     */
    public function startAuthCheck(KernelBootEvent $event){
        /**
         * @var Session $session
         */
        $session = $event->getSession();
        $this->checkSession($session);
        

    }

    /**
     * @param Session|null $session
     */
    private function checkSession(Session $session = null)
    {
        if(!is_null($session)){

            if($session->isStart()){
                $this->checkUserSigned($session);
            }else{
                $session->start();
                $this->checkUserSigned($session);
            }

        }else{
            throw new \LogicException('Authentication Checker does not found Session');
        }
    }

    private function setUserSigned($sign)
    {
        $this->user_signed = $sign;
    }

    private function checkUserSigned(Session $session)
    {
        if(!$session->has('user_signed')){
            $session->set('user_signed',Authentication::AUTH_ROLE_ANONYMOUSLY);
            $this->setUserSigned(Authentication::AUTH_ROLE_ANONYMOUSLY);
        }else{
            $session->get('token');
        }
    }
}