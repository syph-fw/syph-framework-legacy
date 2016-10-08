<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 08/10/2016
 * Time: 01:08
 */

namespace Syph\EventDispatcher\Interfaces;


interface EventListernerInterface
{
    public function getListeners();
}