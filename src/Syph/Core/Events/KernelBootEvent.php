<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 08/10/2016
 * Time: 12:30
 */

namespace Syph\Core\Events;


use Interop\Container\ContainerInterface;
use Syph\EventDispatcher\Interfaces\EventInterface;
use Syph\Http\Session\Session;

class KernelBootEvent implements EventInterface
{

    private $container;

    /**
     * KernelBootEvent constructor.
     * @param $kernel
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSession()
    {
        return $this->container->get(Session::SERVICE_NAME);
    }

    public function getInfo()
    {
        return 'TEST';
    }
}