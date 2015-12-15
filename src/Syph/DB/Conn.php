<?php
namespace Syph\DB;
use Syph\DB\Interfaces\ConnParametersInterface;

/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 13/08/2015
 * Time: 14:44
 */
class Conn
{
    public static $instance = array();

    private function __construct() {
        //
    }

    private static function create(ConnParametersInterface $params)
    {
        return new \PDO(
            'mysql:host='.$params->getHost().';dbname='.$params->getDB(), $params->getUser(), $params->getPass(),
            array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    }

    public static function getInstance($params = null) {

        if (!isset(self::$instance[$params->getName()])) {
            if($params instanceof ConnParametersInterface){
                self::$instance[$params->getName()] = self::create($params);
                self::$instance[$params->getName()]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$instance[$params->getName()]->setAttribute(\PDO::ATTR_ORACLE_NULLS, \PDO::NULL_EMPTY_STRING);
            }
        }

        return self::$instance[$params->getName()];
    }


}