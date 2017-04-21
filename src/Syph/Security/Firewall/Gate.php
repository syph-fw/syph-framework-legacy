<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 05/04/2017
 * Time: 22:01
 */

namespace Syph\Security\Firewall;


use Syph\Routing\Route;
use Syph\Routing\RouteAuthPass;

class Gate
{
    private $name;
    private $prefix;
    private $login_path;
    private $routes = [];
    private $redirect_path;
    /**
     * @var GateGuardian $guardian
     */
    private $guardian;
    private $app_key;

    /**
     * FirewallConfig constructor.
     * @param $name
     * @param $prefix
     * @param $login_path
     */
    public function __construct($name = null, $prefix = null, $login_path = null)
    {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->login_path = $login_path;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Gate
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param mixed $prefix
     * @return Gate
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLoginPath()
    {
        return $this->login_path;
    }

    /**
     * @param mixed $login_path
     * @return Gate
     */
    public function setLoginPath($login_path)
    {
        $this->login_path = $login_path;
        return $this;
    }

    /**
     * @param GateGuardian $guardian
     * @return $this
     */
    public function setGuardian(GateGuardian $guardian)
    {
        $this->guardian = $guardian;
        return $this;
    }

    /**
     * @return GateGuardian
     */
    public function getGuardian()
    {
        return $this->guardian;
    }

    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
        $route->setGate($this);
        return $this;
    }

    public function validatePass(RouteAuthPass $pass)
    {
        $this->guardian->checkAuth($pass);
    }

    /**
     * @return mixed
     */
    public function getRedirectPath()
    {
        return $this->redirect_path;
    }

    /**
     * @param mixed $redirect_path
     * @return Gate
     */
    public function setRedirectPath($redirect_path)
    {
        $this->redirect_path = $redirect_path;
        return $this;
    }

    public function setAppKey($app_key)
    {
        $this->app_key = $app_key;
        return $this;
    }

    public function getAppKey()
    {
        return $this->app_key;
    }

}