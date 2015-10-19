<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 29/09/2015
 * Time: 22:40
 */

namespace Syph\Routing;


use Syph\DependencyInjection\ServiceInterface;

class Routing implements ServiceInterface{


    public function getName()
    {
        return 'routing.core';
    }
}