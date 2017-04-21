<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 13/10/2016
 * Time: 01:50
 */

namespace Syph\Security\Auth;


use Syph\Http\Base\Request;
use Syph\Http\Session\Session;
use Syph\Routing\RouteAuthPass;

class AuthenticationChecker
{
    private $session;
    private $request;

    /**
     * AuthenticationChecker constructor.
     * @param Session $session
     * @param Request $request
     */
    public function __construct(Session $session,Request $request)
    {
        $this->session = $session;
        $this->request = $request;
    }


    public function getListeners()
    {
        return ['kernel.boot'=> [$this,'startAuthCheck']];
    }

    /**
     * @param Session $session
     * @param Request $request
     */
    public function startAuthCheck(RouteAuthPass $pass){

        $this->checkSession($this->session,$pass);
    }

    /**
     * @param Session|null $session
     */
    private function checkSession(Session $session = null, RouteAuthPass $pass)
    {
        if(!is_null($session)){

            if($session->isStart()){
                $this->checkUserSigned($session,$pass);
            }else{
                $session->start();
                $this->checkUserSigned($session,$pass);
            }

        }else{
            throw new \LogicException('Authentication Checker does not found Session');
        }
    }

    private function checkUserSigned(Session $session, RouteAuthPass $pass)
    {
        if(!$session->has('user_signed')){
            $pass->setIsAuthenticated(false);
        }else{
            $token = $session->get('user_signed');
            $this->checkAuthenticatedUser($token);
            $pass->setIsAuthenticated(true);
        }
    }

    private function checkAuthenticatedUser($request)
    {
        
    }
}