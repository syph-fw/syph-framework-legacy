<?php
/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 10/09/2015
 * Time: 17:11
 */

namespace Syph\AppBuilder;


use Syph\Autoload\ClassLoader;
use Syph\AppBuilder\Interfaces\EnvironmentInterface;

class Environment implements EnvironmentInterface
{
    private $loaded;
    private $env = array();

    /**
     * @return mixed
     */
    public function getLoaded()
    {
        return $this->loaded;
    }

    /**
     * @param mixed $loaded
     */
    public function setLoaded(ClassLoader $loaded)
    {
        $this->loaded[] = $loaded;
    }

    /**
     * @return array
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param array $env
     */
    public function setEnv(Array $env)
    {
        $this->env = $env;
    }


}