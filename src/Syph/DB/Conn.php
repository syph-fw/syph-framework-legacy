<?php
namespace Syph\DB;
use Syph\DependencyInjection\ServiceInterface;

/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 13/08/2015
 * Time: 14:44
 */
class Conn implements ServiceInterface
{
    public static $instance;

    private function __construct() {
        //
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new PDO('mysql:host=52.31.153.157;dbname=VendingMachine', 'anarciso', 'amsterdan',
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }

        return self::$instance;
    }


    public function getName()
    {
        return 'connection';
    }
}