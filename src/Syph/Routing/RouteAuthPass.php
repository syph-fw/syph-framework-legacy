<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 08/04/2017
 * Time: 18:36
 */

namespace Syph\Routing;


class RouteAuthPass
{
    private $is_authenticated;
    private $is_authorized;
    private $redirect_path;

    /**
     * @return mixed
     */
    public function getIsAuthenticated()
    {
        return $this->is_authenticated;
    }

    /**
     * @param mixed $is_authenticated
     * @return RouteAuthPass
     */
    public function setIsAuthenticated($is_authenticated)
    {
        $this->is_authenticated = $is_authenticated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsAuthorized()
    {
        return $this->is_authorized;
    }

    /**
     * @param mixed $is_authorized
     * @return RouteAuthPass
     */
    public function setIsAuthorized($is_authorized)
    {
        $this->is_authorized = $is_authorized;
        return $this;
    }

    public function recusePass()
    {
        $this->is_authenticated = false;
        $this->is_authorized = false;
    }

    public function acceptPass()
    {
        $this->is_authenticated = true;
        $this->is_authorized = true;
    }

    public function isAuthenticated()
    {
        return $this->is_authenticated;
    }

    public function isAuthorized()
    {
        return $this->is_authorized;
    }

    public function isValid()
    {
        return $this->is_authenticated == true && $this->is_authorized == true;
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
     * @return RouteAuthPass
     */
    public function setRedirectPath($redirect_path)
    {
        $this->redirect_path = $redirect_path;
        return $this;
    }

}