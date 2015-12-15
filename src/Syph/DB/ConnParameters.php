<?php
/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 31/08/2015
 * Time: 15:02
 */

namespace Syph\DB;


use Syph\DB\Interfaces\ConnParametersInterface;

class ConnParameters implements ConnParametersInterface
{
    private $name;
    private $host;
    private $user;
    private $pass;
    private $db;

    /**
     * ConnParameters constructor.
     * @param $host
     * @param $user
     * @param $pass
     * @param $db
     */
    public function __construct($name = null,$host = null, $user = null, $pass = null, $db = null)
    {
        $this->name = $name;
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param mixed $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @return mixed
     */
    public function getDB()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDB($db)
    {
        $this->db = $db;
    }

    /**
     * @param mixed $db
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $db
     */
    public function getName()
    {
        return $this->name;
    }



}