<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 08/10/2016
 * Time: 12:30
 */

namespace Syph\Core\Events;


use Syph\Core\Interfaces\SyphKernelInterface;
use Syph\EventDispatcher\Interfaces\EventInterface;

class KernelBootEvent implements EventInterface
{

    private $kernel;

    /**
     * KernelBootEvent constructor.
     * @param $kernel
     */
    public function __construct(SyphKernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }


    public function getInfo()
    {
        return 'TEST';
    }
}