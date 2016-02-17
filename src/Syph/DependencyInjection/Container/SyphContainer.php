<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 29/09/2015
 * Time: 23:02
 */

namespace Syph\DependencyInjection\Container;


use Syph\DependencyInjection\Container\Interfaces\SyphContainerInterface;

abstract class SyphContainer implements SyphContainerInterface{
    /**
     * @var Container $container
     */
    protected $container;

    public function setContainer(Container $container){
        $this->container = $container;
    }

} 