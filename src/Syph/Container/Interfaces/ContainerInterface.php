<?php
/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 27/08/2015
 * Time: 14:54
 */

namespace Container\Interfaces;


interface ContainerInterface
{
    public function get($id);
    public function set();
    public function load();
}