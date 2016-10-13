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
use Syph\Http\Base\Request;
use Syph\Routing\Router;

class RequestStartEvent implements EventInterface
{

    private $request;
    private $router;

    /**
     * KernelBootEvent constructor.
     * @param $kernel
     */
    public function __construct(Request $request,Router $router)
    {
        $this->request = $request;
        $this->router = $router;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }



    public function getInfo()
    {
        return 'TEST';
    }
}