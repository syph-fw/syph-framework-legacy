<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 29/09/2015
 * Time: 23:02
 */

namespace Syph\Container;


use Syph\Container\Interfaces\SyphContainerInterface;

abstract class SyphContainer implements SyphContainerInterface{

    protected $container;

    public function setContainer(Container $container){
        $this->container = $container;
    }

} 