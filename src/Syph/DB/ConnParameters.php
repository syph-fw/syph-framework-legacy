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
    private $env;
    private $database;
    private $name;
    private $host;
    private $user;
    private $pass;
    private $db;

    /**
     * ConnParameters constructor.
     * @param $database
     * @param $host
     * @param $user
     * @param $pass
     * @param $db
     */
    public function __construct($env = null,$database = null,$name = null,$host = null, $user = null, $pass = null, $db = null)
    {
        $this->env      = $env;
        $this->database = $database;
        $this->name     = $name;
        $this->host     = $host;
        $this->user     = $user;
        $this->pass     = $pass;
        $this->db       = $db;
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

    public function getStrToPDO()
    {
        return sprintf('%s://%s:%s@%s/%s',$this->database,$this->user,$this->pass,$this->host,$this->db);
    }

    /**
     * @return mixed
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param mixed $env
     * @return ConnParameters
     */
    public function setEnv($env)
    {
        $this->env = $env;
        return $this;
    }

    /**
     * @return null
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param null $database
     * @return ConnParameters
     */
    public function setDatabase($database)
    {
        $this->database = $database;
        return $this;
    }


}