<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 07/10/2016
 * Time: 23:50
 */

namespace Syph\Security\Firewall;


use Syph\DependencyInjection\ServiceInterface;
use Syph\Http\Base\Request;
use Syph\Routing\Router;
use Syph\Security\SecurityProfile;

class Firewall implements ServiceInterface
{

    const SERVICE_NAME = 'security.firewall';
    const FIREWALL_LOAD_EVENT = 'security.firewall_loaded';
    /**
     * Firewall constructor.
     */
    public function __construct(Request $request, Router $router, SecurityProfile $profile)
    {
        $this->loadSecurityConfig($profile);
        $this->run($router);
    }

    private function run(Router $router)
    {
        $route = $router->getMatcher()->getRouteMatch();
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }

    private function loadSecurityConfig(SecurityProfile $profile)
    {
        
    }


}