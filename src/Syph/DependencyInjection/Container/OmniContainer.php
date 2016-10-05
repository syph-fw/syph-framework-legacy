<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 04/10/2016
 * Time: 19:45
 */

namespace Syph\DependencyInjection\Container;


class OmniContainer
{
    public static $instance;
    private $container = null;

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new OmniContainer();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }


    private function __clone()
    {
    }


    private function __wakeup()
    {
    }
}