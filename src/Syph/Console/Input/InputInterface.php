<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 18/03/2016
 * Time: 09:52
 */

namespace Syph\Console\Input;


interface InputInterface
{

    public function hasArguments();
    public function getArguments();
    public function setArguments();
    public function hasParameters();
    public function getParameters();
    public function setParameters();
    public function getCommand();

}