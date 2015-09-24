<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 24/08/2015
 * Time: 13:53
 */

namespace Syph\AppBuilder\Interfaces;


use Syph\AppBuilder\Environment;

interface BuilderInterface
{
    public function loadApp($appName);
    public function register(Environment $env);
    public function hasApp($appName);

}