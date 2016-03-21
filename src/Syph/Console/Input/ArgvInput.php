<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 17/03/2016
 * Time: 14:33
 */

namespace Syph\Console\Input;


class ArgvInput extends Input
{

    public $args;
    /**
     * ArgvInput constructor.
     */
    public function __construct(array $args = null)
    {
        if(null === $args){
            $args = $_SERVER['argv'];
        }

        array_shift($args);

        $this->args = $args;

        parent::__construct();

    }

    public function printArgs()
    {
        foreach ($this->args as $arg) {
            echo " Arg: ".$arg."\n";
        }

        echo "FIM";

    }
}