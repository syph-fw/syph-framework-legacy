<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 17/01/2017
 * Time: 21:52
 */

namespace Syph\Core;


class ConfigProvider
{
    /**
     * @var ParameterCollection $parameters
     */
    private $parameters;
    private $accept_configs;
    private $app_configs;
    private $global_configs;
    private $internal_configs;
    private $merged_configs;

    const EXT_GLOBAL_CONFIG = '.php';

    private $internal_config_dir;
    private $global_config_dir;
    private $apps_config_dir = [];

    /**
     * ConfigCollection constructor.
     * @param $parameters
     */
    public function __construct()
    {
//        $this->parameters = $parameters;
        $this->loadConfigsDirectories();
        $this->loadAcceptConfigs();
        $this->loadParameters();
    }

    private function loadConfigsDirectories()
    {
        $this->internal_config_dir = sprintf('%s/Config/',realpath(dirname(__FILE__)));
        $this->global_config_dir   = sprintf('%s/global/',APP_REAL_PATH);
    }

    private function loadAcceptConfigs()
    {
        $pathGlobalConfigs = sprintf('%sconfigs%s',$this->internal_config_dir,self::EXT_GLOBAL_CONFIG);
        $this->accept_configs = require_once($pathGlobalConfigs);
    }

    private function loadParameters()
    {
        $parameters = $this->loadConfigFile(sprintf('%sparameters%s',$this->global_config_dir,self::EXT_GLOBAL_CONFIG));
        $this->parameters = new ParameterCollection($parameters);
    }

    public function build()
    {
        $this->loadInternalConfigs();
        $this->loadGlobalConfigs();
        $this->buildParametersOnConfigs();
        $this->generateMergedConfigs($this->internal_configs, $this->global_configs);
    }

    private function loadInternalConfigs()
    {
        $this->internal_configs = $this->loadConfig($this->internal_config_dir);
    }

    private function loadGlobalConfigs()
    {
        $this->global_configs = $this->loadConfig($this->global_config_dir);
    }

    public function loadConfigFile($pathname)
    {
        if(file_exists($pathname))
            return require_once($pathname);
        return false;
    }

    private function loadConfig($path)
    {
        $configs = [];
        $files = new \DirectoryIterator($path);
        foreach ($files as $file) {
            $filename = $file->getBasename(self::EXT_GLOBAL_CONFIG);
            if(in_array($filename,$this->accept_configs)){
                $configs[$filename] = require_once($file->getPathname());
            }
        }
        return $configs;
    }

    private function generateMergedConfigs($config1, $config2)
    {
        $this->merged_configs = array_merge_recursive($config1, $config2);
    }

    public function getConfigApp($appName){
        return $this->app_configs[$appName];
    }

    public function getConfig($name = null)
    {
        if(null === $name)
        {
            return $this->merged_configs;
        }

        if (array_key_exists($name,$this->merged_configs))
        {
            return $this->merged_configs[$name];
        }
        return null;
    }

    public function getInternalConfig()
    {
        return $this->internal_configs;
    }

    public function getGlobalConfig()
    {
        return $this->global_configs;
    }


    private function buildParametersOnConfigs()
    {
        $this->internal_configs = $this->buildParametersOnConfig($this->internal_configs);
        $this->global_configs = $this->buildParametersOnConfig($this->global_configs);
    }

    private function buildParametersOnConfig($configs){
        foreach ($configs as $key=>$config) {
            if(is_array($config)){
                $configs[$key] = $this->buildParametersOnConfig($config);
            }else{
                $matches = [];
                $param = null;
                preg_match_all('#\{\w+\}#', $config, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

                if(count($matches) > 0){
                    foreach ($matches as $match) {
                        $varName = substr($match[0][0], 1, -1);
                        $value = $this->parameters->get($varName);
                        $configs[$key] = $value;
                    }

                }

            }
        }
        return $configs;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function addConfigAppDir($appName,$dir)
    {
        $this->apps_config_dir[$appName] = $dir;
    }

    public function buildConfigApp($appName)
    {
        $this->app_configs[$appName] = $this->loadConfig($this->apps_config_dir[$appName]);
        $this->app_configs[$appName] = $this->buildParametersOnConfig($this->app_configs[$appName]);
//        $this->generateMergedConfigs($this->global_configs, $this->app_configs[$appName]);
    }
}