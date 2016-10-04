<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 01/07/2016
 * Time: 09:50
 */

namespace Syph\Exception;


class MasterHandle
{
    private $debug;

    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    public static function register($debug = true)
    {
        return new static($debug);

    }

}