<?php
namespace Container;
use Container\Interfaces\ContainerInterface;

/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 26/08/2015
 * Time: 14:47
 */
class Container implements ContainerInterface
{
    public function __construct()
    {
        $this->load();
    }

    public function get($id)
    {
        // TODO: Implement get() method.
    }

    public function set()
    {
        // TODO: Implement set() method.
    }

    public function load()
    {
        // TODO: Implement load() method.
    }
}