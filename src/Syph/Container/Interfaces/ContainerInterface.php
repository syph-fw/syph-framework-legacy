<?php
/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 27/08/2015
 * Time: 14:54
 */

namespace Syph\Container\Interfaces;


use Syph\DependencyInjection\ServiceInterface;

interface ContainerInterface
{
    public function get($id);
    public function set(ServiceInterface $service);
    public function load($serviceArray);
}