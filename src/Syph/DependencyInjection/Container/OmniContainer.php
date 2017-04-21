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
    /**
     * @var OmniContainer $instance
     */
    public static $instance;
    /**
     * @var Container $container
     */
    private $container = null;

    /**
     * @return Container
     */
    public function getContainer()
    {
        return self::$instance->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        self::$instance->container = $container;
    }

    /**
     * @return OmniContainer
     */
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