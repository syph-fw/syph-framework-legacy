<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 08/04/2017
 * Time: 18:33
 */

namespace Syph\Security\Firewall;


use Syph\Http\Base\Request;
use Syph\Http\Session\Session;
use Syph\Routing\Route;
use Syph\Routing\RouteAuthPass;
use Syph\Security\Auth\AuthenticationChecker;
use Syph\Security\Auth\User\UserGenericInterface;
use Syph\Security\Crypt\Cube;

class GateGuardian
{

    /**
     * @var Gate $gate
     */
    private $gate;
    /**
     * @var AuthenticationChecker $authenticationChecker
     */
    private $authenticationChecker;
    /**
     * @var Session$session
     */
    private $session;
    /**
     * @var Request $request
     */
    private $request;

    /**
     * GateGuardian constructor.
     * @param Gate $gate
     */
    public function __construct(Gate $gate, Session $session,Request $request)
    {
        $this->session = $session;
        $this->request = $request;
        $this->gate = $gate;
    }

    /**
     * @return Gate
     */
    public function getGate()
    {
        return $this->gate;
    }

    /**
     * @param Gate $gate
     * @return GateGuardian
     */
    public function setGate($gate)
    {
        $this->gate = $gate;
        return $this;
    }

    public function checkAuth(RouteAuthPass $pass)
    {
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
            $this->checkRedirectPath($this->gate, $pass);
        }else{
            $pass->acceptPass();
        }
    }

    private function checkRedirectPath(Gate $gate,RouteAuthPass $pass)
    {
        $redirect_path = $gate->getRedirectPath();
        if(is_null($redirect_path)){
            return;
        }
        $pass->setRedirectPath($redirect_path);
    }

    public function authUser(UserGenericInterface $user)
    {
        $token = Cube::generateHash($user->getUsername(), $this->gate->getAppKey());
        $this->setUserSigned($user,$token);
    }

    private function setUserSigned($user,$token)
    {
        $this->session->set('user_signed', $token);
        $this->session->set('user', $user);
    }

    public function unAuthUser()
    {
        $this->session->remove('user');
        $this->session->remove('user_signed');
    }

}