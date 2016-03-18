<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 18/03/2016
 * Time: 16:05
 */

namespace Syph\Console\Input;


interface CommandInterface
{
    public function getName();
    public function setName($name);
    public function run();
}