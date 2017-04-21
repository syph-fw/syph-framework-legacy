<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 05/04/2017
 * Time: 21:46
 */

namespace Syph\Security;


class SecurityConfig
{

    private $firewall_config;
    private $roles_config;
    private $app_key;

    /**
     * SecurityConfig constructor.
     */
    public function __construct(array $config)
    {
        $this->loadAppKey($config);
        $this->loadFirewallConfigs($config);
        $this->loadRolesConfigs($config);
    }

    private function loadFirewallConfigs($config)
    {
        if(array_key_exists('firewall',$config)){
            $this->firewall_config = $config['firewall'];
        }
    }

    private function loadRolesConfigs($config)
    {
        if(array_key_exists('roles',$config)){
            $this->roles_config = $config['roles'];
        }
    }

    private function loadAppKey($config)
    {
        if(array_key_exists('app_key',$config)){
            $this->app_key = $config['app_key'];
        }
    }

    public function getSecurityConfigs()
    {
        return [
            $this->app_key,
            $this->roles_config,
            $this->firewall_config
        ];
    }

    /**
     * @return mixed
     */
    public function getFirewallConfig()
    {
        return $this->firewall_config;
    }

    /**
     * @param mixed $firewall_config
     * @return SecurityConfig
     */
    public function setFirewallConfig($firewall_config)
    {
        $this->firewall_config = $firewall_config;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRolesConfig()
    {
        return $this->roles_config;
    }

    /**
     * @param mixed $roles_config
     * @return SecurityConfig
     */
    public function setRolesConfig($roles_config)
    {
        $this->roles_config = $roles_config;
        return $this;
    }

    public function setAppKey($appKey)
    {
        $this->app_key = $appKey;
        return $this;
    }

    public function getAppKey()
    {
        return $this->app_key;
    }

}