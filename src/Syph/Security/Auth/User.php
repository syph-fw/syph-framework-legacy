<?php
/**
 * Created by PhpStorm.
 * User: btlouvem@gmail.com
 * Date: 07/10/2016
 * Time: 23:40
 */

namespace Syph\Security\Auth;


class User implements UserGenericInterface
{

    private $username;
    private $password;
    private $enabled;

    /**
     * User constructor.
     * @param $username
     * @param $password
     * @param $enabled
     */
    public function __construct($username, $password, $enabled = true)
    {
        $this->username = $username;
        $this->password = $password;
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }


    /**
     * @param mixed $enabled
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }



    public function isEnabled()
    {
        return $this->enabled;
    }
}