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

    public $commands;
    public $options;
    public $params;
    /**
     * ArgvInput constructor.
     */
    public function __construct(array $args = null)
    {
        if(null === $args){
            $args = $_SERVER['argv'];
        }

        array_shift($args);

        $this->arguments = $args;

        parent::__construct();

    }

    public function parse()
    {
        InputParser::parse($this);
    }

    public function printArgs()
    {
        echo "\n---------------- \n";
        echo "\n";
        echo "\nSTART PRINT ARGS \n";
        echo "\n";
        foreach ($this->arguments as $arg) {
            echo " Arg: ".$arg."\n";
        }
        echo "\n";
        echo "FIM";
        echo "\n";
        echo "\n---------------- \n";
    }
}