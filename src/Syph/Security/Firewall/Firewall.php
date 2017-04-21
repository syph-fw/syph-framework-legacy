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
use Syph\Http\Session\Session;
use Syph\Routing\Route;
use Syph\Routing\Router;
use Syph\Security\Auth\AuthenticationChecker;
use Syph\Security\Auth\AuthenticationCheker;
use Syph\Security\Auth\User\UserGenericInterface;
use Syph\Security\Auth\UserAuthenticatedList;
use Syph\Security\Csrf\Csrf;
use Syph\Security\SecurityConfig;
use Syph\Security\SecurityProfile;

class Firewall implements ServiceInterface
{

    const SERVICE_NAME = 'security.firewall';
    const FIREWALL_LOAD_EVENT = 'security.firewall_loaded';
    /**
     * @var array|Gate $gates
     */
    private $gates = [];
    private $authenticationChecker;
    private $securityConfig;

    /**
     * Firewall constructor.
     */
    public function __construct(Session $session, Request $request, Router $router, SecurityConfig $securityConfig)
    {
        $this->securityConfig = $securityConfig;
        $this->loadSecurityConfig(
            $session,
            $request,
            $securityConfig->getFirewallConfig(),
            $securityConfig->getAppKey()
        );
        $this->up($router);
        $this->loadCsrfProtection($session);

    }

    private function up(Router $router)
    {
        $routes = $router->getCollection();
        $this->bindFirewallOnRoutes($routes);
    }

    private function bindFirewallOnRoutes($routes)
    {
        foreach ($this->gates as $gate) {
            $prefix = $gate->getPrefix();

            array_walk_recursive($routes,function ($route) use ($prefix, $gate){

                if($route instanceof Route){

                    $posPrefixOnPattern = strpos($route->getPattern(), $prefix);
                    if(false !== $posPrefixOnPattern && 0 === $posPrefixOnPattern){
                        $gate->addRoute($route);
                    }
                }
            });
        }
    }

    private function loadSecurityConfig(Session $session, Request $request,array $firewallConfigs, $app_key)
    {
        foreach ($firewallConfigs as $name => $firewallConfig) {
            $this->gates[$name] = $this->createFirewallGate($name,$firewallConfig, $session, $request, $app_key);
        }
    }

    private function createFirewallGate($firewall_name,$firewallConfig,$session, $request, $app_key)
    {
        $gate = new Gate($firewall_name);
        if(array_key_exists('prefix',$firewallConfig)){
            $gate->setPrefix($firewallConfig['prefix']);
        }

        if (array_key_exists('login_path',$firewallConfig)){
            $gate->setLoginPath($firewallConfig['login_path']);
        }

        if (array_key_exists('redirect_path',$firewallConfig)){
            $gate->setRedirectPath($firewallConfig['redirect_path']);
        }

        $guardian = $this->createGateGuardian($gate,$session, $request);
        $gate->setGuardian($guardian);
        $gate->setAppKey($app_key);
        return $gate;
    }

    /**
     * @param Gate $gate
     * @return GateGuardian
     */
    private function createGateGuardian(Gate $gate, Session $session, Request $request)
    {
        return new GateGuardian($gate,$session,$request);
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }

    public function getGate($gate_name)
    {
        return $this->gates[$gate_name];
    }

    public function getAuthenticationChecker()
    {
        return $this->authenticationChecker;
    }

    public function authUserOnFirewall($gate_name,UserGenericInterface $user)
    {
        /**
         * @var Gate $gate
         */
        $gate = $this->gates[$gate_name];
        $guardian = $gate->getGuardian();

        $guardian->authUser($user);
    }

    public function removeUserOnFirewall($gate_name)
    {
        /**
         * @var Gate $gate
         */
        $gate = $this->gates[$gate_name];
        $guardian = $gate->getGuardian();

        $guardian->unAuthUser();
    }

    private function loadCsrfProtection(Session $session)
    {
        $this->csrf = new Csrf($session);
    }

}