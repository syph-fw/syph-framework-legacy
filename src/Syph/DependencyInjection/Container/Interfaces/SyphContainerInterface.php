<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 29/09/2015
 * Time: 23:26
 */

namespace Syph\DependencyInjection\Container\Interfaces;


use Syph\DependencyInjection\Container\Container;

interface SyphContainerInterface {
    public function setContainer(Container $container);
} 