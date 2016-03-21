<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 17/03/2016
 * Time: 14:19
 */

namespace Syph\Console\Input;


abstract class Input implements InputInterface
{


    /**
     * Input constructor.
     */
    public function __construct()
    {

    }

    public function hasArguments()
    {
        // TODO: Implement hasArguments() method.
    }

    public function getArguments()
    {
        // TODO: Implement getArguments() method.
    }

    public function setArguments()
    {
        // TODO: Implement setArguments() method.
    }

    public function hasParameters()
    {
        // TODO: Implement hasParameters() method.
    }

    public function getParameters()
    {
        // TODO: Implement getParameters() method.
    }

    public function setParameters()
    {
        // TODO: Implement setParameters() method.
    }

    /**
     * @return Command
     */
    public function getCommand()
    {
        return new Command('SyphWake');
    }

}