<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 17/03/2016
 * Time: 13:35
 */

namespace Syph\Console;


use Syph\Core\Interfaces\SyphKernelInterface;

class ConsoleApp
{
    private $_kernel;
    /**
     * ConsoleApp constructor.
     */
    public function __construct(SyphKernelInterface $kernel)
    {
        $this->_kernel = $kernel;
    }



}