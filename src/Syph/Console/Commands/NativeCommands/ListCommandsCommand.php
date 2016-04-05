<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 23/03/2016
 * Time: 14:33
 */

namespace Syph\Console\Commands\NativeCommands;


use Syph\Console\Commands\Command;

class ListCommandsCommand extends Command
{
    public function run(){
        print __METHOD__."\n";
    }
}