<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 21/03/2016
 * Time: 15:52
 */

namespace Syph\Console\Input;


class InputParser
{
    private static $args = array();

    public static function parse(ArgvInput $input)
    {
        self::$args = $input->getArguments();

        foreach(self::$args as $arg){


        }

    }
}