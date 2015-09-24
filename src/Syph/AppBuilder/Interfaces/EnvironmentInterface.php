<?php
/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 10/09/2015
 * Time: 17:12
 */

namespace Syph\AppBuilder\Interfaces;


use Syph\Autoload\ClassLoader;

interface EnvironmentInterface
{
    public function getLoaded();
    public function setLoaded(ClassLoader $loader);
    public function getEnv();
    public function setEnv(array $env);

}