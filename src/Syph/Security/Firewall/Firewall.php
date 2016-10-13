<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 07/10/2016
 * Time: 23:50
 */

namespace Syph\Security\Firewall;


use Syph\Http\Base\Request;
use Syph\Routing\Router;

class Firewall
{

    /**
     * Firewall constructor.
     */
    public function __construct(Request $request, Router $router)
    {
        $this->run($router);
    }

    private function run(Router $router)
    {
        $route = $router->getMatcher()->getRouteMatch();

    }
}